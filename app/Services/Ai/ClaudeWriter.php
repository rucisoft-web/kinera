<?php

namespace App\Services\Ai;

use Anthropic\Client;
use RuntimeException;

/** Satu permintaan ke Claude Messages API dengan keluaran JSON terstruktur sesuai skema. */
class ClaudeWriter
{
    public function available(): bool
    {
        return config('services.anthropic.enabled') && filled(config('services.anthropic.key'));
    }

    public function model(): string
    {
        return (string) config('services.anthropic.model');
    }

    /**
     * @param  array<int,array>  $content  blok konten pesan pengguna (dokumen lampiran lalu teks)
     * @return array{data: array, input_tokens: int, output_tokens: int, model: string}
     */
    public function write(string $system, array $content, array $schema): array
    {
        $client = new Client(apiKey: (string) config('services.anthropic.key'));

        $message = $client->messages->create(
            model: $this->model(),
            maxTokens: 16000,
            system: $system,
            messages: [['role' => 'user', 'content' => $content]],
            thinking: ['type' => 'adaptive'],
            outputConfig: [
                'effort' => (string) config('services.anthropic.effort'),
                'format' => ['type' => 'json_schema', 'schema' => $schema],
            ],
            requestOptions: ['timeout' => 280.0],
        );

        if ($message->stopReason === 'refusal') {
            throw new RuntimeException('Claude menolak permintaan ini'.($message->stopDetails?->explanation ? ': '.$message->stopDetails->explanation : '.'));
        }
        if ($message->stopReason === 'max_tokens') {
            throw new RuntimeException('Jawaban Claude terpotong karena terlalu panjang. Kurangi lampiran atau persempit instruksi.');
        }

        $data = null;
        foreach ($message->content as $block) {
            if ($block->type === 'text') {
                $data = json_decode($block->text, true);
                break;
            }
        }
        if (! is_array($data)) {
            throw new RuntimeException('Jawaban Claude tidak dapat dibaca sebagai JSON.');
        }

        return [
            'data' => $data,
            'input_tokens' => (int) $message->usage->inputTokens,
            'output_tokens' => (int) $message->usage->outputTokens,
            'model' => (string) $message->model,
        ];
    }
}

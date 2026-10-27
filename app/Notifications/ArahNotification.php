<?php

namespace App\Notifications;

use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/** Notifikasi umum Ruci BSC: tampil di lonceng Filament (database) dan dikirim via email bila diaktifkan. */
class ArahNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $body,
        public ?string $url = null,
        public string $status = 'info',
    ) {}

    public function via(object $notifiable): array
    {
        return config('bsc.notify_mail') ? ['database', 'mail'] : ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $n = FilamentNotification::make()->title($this->title)->body($this->body)->status($this->status);
        if ($this->url) {
            $n->actions([Action::make('buka')->label('Buka')->url($this->url)->markAsRead()]);
        }

        return $n->getDatabaseMessage();
    }

    public function toMail(object $notifiable): MailMessage
    {
        $m = (new MailMessage)->subject('[Ruci BSC] '.$this->title)->greeting('Yth. '.$notifiable->name)->line($this->body);
        if ($this->url) {
            $m->action('Buka Ruci BSC', $this->url);
        }

        return $m->salutation('Salam, Ruci BSC Professional');
    }
}

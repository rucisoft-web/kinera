<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Peta strategi sektor publik menempatkan pemangku kepentingan di puncak dan keuangan sebagai penopang.
 * Panah lama pada data demo (pemangku kepentingan -> keuangan) dibalik menjadi keuangan -> pemangku kepentingan.
 * Data instansi sungguhan tidak disentuh.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->flip('pelanggan', 'keuangan');
    }

    public function down(): void
    {
        $this->flip('keuangan', 'pelanggan');
    }

    private function flip(string $from, string $to): void
    {
        $rows = DB::table('objective_relations as r')
            ->join('strategic_objectives as s', 's.id', '=', 'r.source_objective_id')
            ->join('strategic_objectives as t', 't.id', '=', 'r.target_objective_id')
            ->join('companies as c', 'c.id', '=', 'r.company_id')
            ->where('c.is_demo', true)->where('s.perspective', $from)->where('t.perspective', $to)
            ->get(['r.id', 'r.source_objective_id', 'r.target_objective_id']);

        foreach ($rows as $r) {
            DB::table('objective_relations')->where('id', $r->id)
                ->update(['source_objective_id' => $r->target_objective_id, 'target_objective_id' => $r->source_objective_id]);
        }
    }
};

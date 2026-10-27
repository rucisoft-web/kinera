<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Edisi BLU (PER-6/PB/2025): profil satker BLU, atribut Indikator Kinerja Kontrak Kinerja
 * (aspek, wajib dicapai, capaian maksimum, Definisi Operasional), dan dokumen Kontrak Kinerja beserta perubahannya.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $t) {
            $t->string('blu_field', 30)->nullable()->after('industry');          // kesehatan | pendidikan | lainnya
            $t->string('ministry')->nullable()->after('blu_field');              // Kementerian Negara/Lembaga
            $t->string('technical_supervisor')->nullable()->after('ministry');   // Pembina Teknis (unit eselon I)
            $t->string('leader_title', 100)->nullable()->after('technical_supervisor');
            $t->string('leader_name')->nullable()->after('leader_title');
            $t->string('leader_nip', 30)->nullable()->after('leader_name');
            $t->string('dg_name')->nullable()->after('leader_nip');              // Direktur Jenderal Perbendaharaan
            $t->string('dg_nip', 30)->nullable()->after('dg_name');
            $t->date('blu_since')->nullable()->after('dg_nip');
            $t->string('remuneration_decree')->nullable()->after('blu_since');   // KMK remunerasi (dasar capaian maksimum)
        });

        Schema::table('kpis', function (Blueprint $t) {
            $t->string('aspect', 20)->default('layanan')->after('strategic_objective_id'); // layanan | keuangan | penugasan
            $t->boolean('is_mandatory')->default(false)->after('aspect');
            $t->decimal('max_achievement', 6, 2)->nullable()->after('is_mandatory');
            $t->string('incentive_role', 20)->nullable()->after('max_achievement'); // pnbp | ebitda | opini: dipakai uji syarat insentif Pasal 14 ayat (4)
            $t->text('operational_definition')->nullable()->after('formula');
            $t->text('data_collection')->nullable()->after('data_source');
            $t->text('test_method')->nullable()->after('data_collection');
            $t->text('supporting_documents')->nullable()->after('test_method');
        });

        Schema::create('performance_contracts', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->string('code', 40)->nullable();
            $t->unsignedSmallInteger('version')->default(0);
            $t->string('kind', 20)->default('awal');            // awal | perubahan | adendum
            $t->string('status', 20)->default('draf');          // draf | diusulkan | disepakati | ditandatangani | digantikan
            $t->string('number')->nullable();
            $t->string('basis', 40)->nullable();                // dasar perubahan (Pasal 19 ayat 1) / peralihan (Pasal 21)
            $t->text('reason')->nullable();
            $t->string('technical_approval')->nullable();       // surat persetujuan Pembina Teknis
            $t->date('proposed_at')->nullable();
            $t->date('discussed_at')->nullable();
            $t->string('ba_number')->nullable();                // berita acara hasil pembahasan (Lampiran II)
            $t->date('leader_signed_at')->nullable();
            $t->date('dg_signed_at')->nullable();
            $t->string('signed_place', 100)->nullable();
            $t->boolean('legal_case_free')->nullable();         // Pasal 14 ayat (4) huruf g
            $t->boolean('other_requirements_met')->nullable();  // Pasal 14 ayat (4) huruf h
            $t->text('incentive_notes')->nullable();
            $t->json('indicators')->nullable();
            $t->text('notes')->nullable();
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->unique(['analysis_period_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_contracts');
        Schema::table('kpis', fn (Blueprint $t) => $t->dropColumn(['aspect', 'is_mandatory', 'max_achievement', 'incentive_role', 'operational_definition', 'data_collection', 'test_method', 'supporting_documents']));
        Schema::table('companies', fn (Blueprint $t) => $t->dropColumn(['blu_field', 'ministry', 'technical_supervisor', 'leader_title', 'leader_name', 'leader_nip', 'dg_name', 'dg_nip', 'blu_since', 'remuneration_decree']));
    }
};

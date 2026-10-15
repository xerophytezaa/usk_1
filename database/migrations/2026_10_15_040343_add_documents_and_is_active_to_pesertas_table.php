<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pesertas', function (Blueprint $table) {
            if (! Schema::hasColumn('pesertas', 'file_ktp')) {
                $table->string('file_ktp')->nullable()->after('alamat');
            }
            if (! Schema::hasColumn('pesertas', 'file_ijazah')) {
                $table->string('file_ijazah')->nullable();
            }
            if (! Schema::hasColumn('pesertas', 'file_sertifikat')) {
                $table->string('file_sertifikat')->nullable();
            }
            if (! Schema::hasColumn('pesertas', 'is_active')) {
                $table->boolean('is_active')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesertas', function (Blueprint $table) {
            $columns = [];
            
            if (Schema::hasColumn('pesertas', 'file_ktp')) $columns[] = 'file_ktp';
            if (Schema::hasColumn('pesertas', 'file_ijazah')) $columns[] = 'file_ijazah';
            if (Schema::hasColumn('pesertas', 'file_sertifikat')) $columns[] = 'file_sertifikat';
            if (Schema::hasColumn('pesertas', 'is_active')) $columns[] = 'is_active';

            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};

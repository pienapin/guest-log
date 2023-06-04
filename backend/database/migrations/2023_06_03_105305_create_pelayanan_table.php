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
        Schema::create('pelayanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kunjungan_id')
                ->nullable()
                ->constrained('kunjungan')
                ->nullOnDelete();
            $table->foreignId('petugas_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('data_diminta')->nullable();
            $table->string('dokumentasi')->nullable();
            $table->enum('status_layanan', ['Terlayani', 'Terlayani Sebagian', 'Tidak Terlayani'])->nullable();
            $table->longText('keterangan_pelayanan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelayanan');
    }
};

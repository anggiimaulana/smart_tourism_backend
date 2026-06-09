<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('nama');
            $table->string('jenis_kelamin')->nullable();
            $table->integer('usia')->nullable();
            $table->string('pekerjaan');
            $table->string('institusi')->nullable();
            $table->string('domisili')->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->string('tujuan_kunjungan')->nullable();
            $table->string('frekuensi_kunjungan')->nullable();
            $table->string('fitur_favorit')->nullable();
            $table->integer('kemudahan_navigasi')->nullable();
            $table->integer('kecepatan_akses')->nullable();
            $table->integer('tampilan_desain')->nullable();
            $table->integer('kelengkapan_konten')->nullable();
            $table->integer('rating')->nullable();
            $table->text('kesan')->nullable();
            $table->text('pesan')->nullable();
            $table->text('saran')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questionnaires');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('kuliner')) {
            return;
        }

        Schema::create('kuliner', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid')->unique();
            $table->string('kode', 20)->unique();
            $table->string('id_wisata_terdekat', 20)->nullable();
            $table->string('nama', 255);
            $table->string('wilayah', 50)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('jenis_tempat', 50)->nullable();
            $table->string('kategori_menu_utama', 100)->nullable();
            $table->text('menu_unggulan')->nullable();
            $table->boolean('makanan_khas_daerah')->default(false);
            $table->string('nama_makanan_khas', 255)->nullable();
            $table->integer('harga_menu_min')->nullable();
            $table->integer('harga_menu_max')->nullable();
            $table->time('jam_buka')->nullable();
            $table->time('jam_tutup')->nullable();
            $table->integer('kapasitas_orang')->nullable();
            $table->boolean('sertifikat_halal')->default(false);
            $table->decimal('rating_google', 3, 1)->nullable();
            $table->integer('jumlah_ulasan_google')->nullable();
            $table->text('link_google_maps')->nullable();
            $table->text('kontak')->nullable();
            $table->text('sumber_data')->nullable();
            $table->text('catatan')->nullable();
            $table->string('status', 20)->default('draft');
            $table->string('sentimen', 20)->nullable();
            $table->decimal('skor_sentimen', 5, 4)->nullable();
            $table->integer('total_ulasan_scraped')->default(0);
            $table->integer('total_positif')->default(0);
            $table->integer('total_negatif')->default(0);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE kuliner ADD COLUMN fasilitas TEXT[] NULL');
        DB::statement('ALTER TABLE kuliner ADD COLUMN gambar TEXT[] NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuliner');
    }
};

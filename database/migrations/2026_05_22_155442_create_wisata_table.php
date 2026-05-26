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
        if (Schema::hasTable('wisata')) {
            return;
        }

        Schema::create('wisata', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid')->unique();
            $table->string('kode', 20)->unique();
            $table->string('nama', 255);
            $table->string('wilayah', 50)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('kategori_utama', 50)->nullable();
            $table->string('sub_kategori', 100)->nullable();
            $table->string('jenis_tempat', 50)->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('harga_tiket_min')->nullable();
            $table->integer('harga_tiket_max')->nullable();
            $table->boolean('gratis')->default(false);
            $table->time('jam_buka')->nullable();
            $table->time('jam_tutup')->nullable();
            $table->string('hari_libur_operasional', 255)->nullable();
            $table->decimal('estimasi_durasi_jam', 4, 1)->nullable();
            $table->string('aksesibilitas', 100)->nullable();
            $table->string('moda_transportasi', 255)->nullable();
            $table->decimal('rating_google', 3, 1)->nullable();
            $table->integer('jumlah_ulasan_google')->nullable();
            $table->text('link_google_maps')->nullable();
            $table->text('link_instagram')->nullable();
            $table->text('link_website')->nullable();
            $table->text('kontak')->nullable();
            $table->text('sumber_data')->nullable();
            $table->string('diinput_oleh', 100)->nullable();
            $table->string('status', 20)->default('draft');
            $table->string('sentimen', 20)->nullable();
            $table->decimal('skor_sentimen', 5, 4)->nullable();
            $table->integer('total_ulasan_scraped')->default(0);
            $table->integer('total_positif')->default(0);
            $table->integer('total_negatif')->default(0);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE wisata ADD COLUMN fasilitas TEXT[] NULL');
        DB::statement('ALTER TABLE wisata ADD COLUMN gambar TEXT[] NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wisata');
    }
};

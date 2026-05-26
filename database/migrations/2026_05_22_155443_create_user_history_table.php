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
        if (Schema::hasTable('user_history')) {
            return;
        }

        Schema::create('user_history', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('tipe_tempat', 20)->nullable();
            $table->integer('tempat_id')->nullable();
            $table->string('tempat_kode', 20)->nullable();
            $table->string('aksi', 20);
            $table->decimal('nilai_rating', 3, 2)->nullable();
            $table->integer('durasi_detik')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_history');
    }
};

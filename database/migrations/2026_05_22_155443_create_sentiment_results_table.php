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
        if (Schema::hasTable('sentiment_results')) {
            return;
        }

        Schema::create('sentiment_results', function (Blueprint $table) {
            $table->id();
            $table->string('tipe_tempat', 20);
            $table->integer('tempat_id')->nullable();
            $table->string('tempat_kode', 20)->nullable();
            $table->text('ulasan_asli')->nullable();
            $table->text('ulasan_bersih')->nullable();
            $table->string('sentimen', 20);
            $table->decimal('confidence', 6, 4)->nullable();
            $table->string('model_used', 30)->nullable();
            $table->string('sumber_scraping', 50)->nullable();
            $table->timestamp('scraped_at')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sentiment_results');
    }
};

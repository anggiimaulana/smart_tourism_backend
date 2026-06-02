<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('llm_configs')) {
            return;
        }

        Schema::create('llm_configs', function (Blueprint $table) {
            $table->id();
            $table->string('provider');           // openai, groq, gemini
            $table->string('base_url')->nullable();
            $table->text('api_key')->nullable();
            $table->string('model');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('llm_configs');
    }
};

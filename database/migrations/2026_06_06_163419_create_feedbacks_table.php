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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('feature', 50); // e.g., 'chatbot', 'recommendation', 'planning'
            $table->integer('rating'); // e.g., 1 to 5, or 1 and -1 (thumbs up/down)
            $table->text('comment')->nullable();
            $table->json('context')->nullable(); // stores what was recommended or the chatbot question/answer
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};

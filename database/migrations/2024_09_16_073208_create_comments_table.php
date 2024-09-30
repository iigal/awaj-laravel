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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_id')
                ->constrained()
                ->onDelete('cascade'); // Reference to complaints table
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade'); // Reference to users table
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('comments')
                ->onDelete('cascade'); // Self-referencing
            $table->text('message'); // To store the comment message
            $table->integer('sorting')->default('0'); // To sort the comment
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};

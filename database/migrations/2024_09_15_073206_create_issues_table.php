<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssuesTable extends Migration
{
    public function up(){
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->json('images')->nullable();
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
            $table->enum('status', ['Success', 'Queue', 'Progress']);
            $table->boolean('is_published')->nullable()->default(false);
            $table->timestamps();
        });
    }   

    public function down()
    {
        Schema::dropIfExists('issues');
    }
}
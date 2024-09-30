<?php

use App\Models\Issue;
use App\Models\Category;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('categories')
                ->onDelete('cascade'); // Self-referencing
            $table->integer('sorting')->default('0');
            $table->timestamps();
        });

        Schema::create('category_issue', static function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Issue::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(Category::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_issue');
        Schema::dropIfExists('categories');
    }
}


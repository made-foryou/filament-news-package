<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $prefix = config('filament-news.database_prefix');
        $table_name = $prefix . '_' . config('filament-news.posts_table_name');
        $categories_table_name = $prefix . '_' . config('filament-news.categories_table_name');

        Schema::create($table_name, function (Blueprint $table) use ($categories_table_name) {
            $table->id();

            $table->string('title');
            $table->string('slug');

            $table->date('date');

            $table->text('summary');
            $table->text('content');

            $table->foreignId('category_id')
                ->references('id')
                ->on($categories_table_name)
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $prefix = config('filament-news.database.prefix');
        $table_name = $prefix . '_' . config('filament-news.database.posts_table');

        Schema::create($table_name, function (Blueprint $table) {
            $table->id();

            $table->string('title');

            $table->date('date');

            $table->text('summary');
            $table->text('content');

            $table->timestamps();
            $table->softDeletes();
        });
    }
};

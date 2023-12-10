<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $prefix = config('filament-news.database_prefix');
        $table_name = $prefix . '_' . config('filament-news.categories_table_name');

        Schema::create($table_name, function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug');

            $table->text('description');

            $table->timestamps();
            $table->softDeletes();
        });
    }
};

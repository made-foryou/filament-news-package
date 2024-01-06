<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $prefix = config('filament-news.database.prefix');
        $table_name = $prefix . '_posts';

        Schema::create($table_name, function (Blueprint $table) {
            $table->id();

            $table->string('title');

            $table->date('date')
                ->nullable();

            $table->text('summary')
                ->nullable();

            $table->text('content')
                ->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }
};

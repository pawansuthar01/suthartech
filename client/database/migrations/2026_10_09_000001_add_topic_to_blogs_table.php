<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table): void {
            $table->string('topic', 80)->default('general')->index();
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table): void {
            $table->dropColumn('topic');
        });
    }
};

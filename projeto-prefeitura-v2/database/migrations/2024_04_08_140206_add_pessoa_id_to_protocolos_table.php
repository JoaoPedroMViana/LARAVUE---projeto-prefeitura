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
        Schema::table('protocolos', function (Blueprint $table) {
            $table->foreignId('pessoa_id')->constrained('pessoas', 'id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('protocolos', function (Blueprint $table) {
            $table->foreignId('pessoa_id')->constrained()->onDelete('cacade');
        });
    }
};

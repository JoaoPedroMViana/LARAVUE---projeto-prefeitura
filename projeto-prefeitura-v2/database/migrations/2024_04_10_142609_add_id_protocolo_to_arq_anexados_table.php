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
        Schema::table('arq_anexados', function (Blueprint $table) {
            $table->foreignId('id_protocolo')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arq_anexados', function (Blueprint $table) {
            $table->foreignId('id_protocolo')->constrained()->onDelete('cacade');
        });
    }
};

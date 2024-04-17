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
            $table->foreignId('protocolo_id')->constrained('protocolos', 'numero')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arq_anexados', function (Blueprint $table) {
            $table->dropForeign(['protocolo_id']);
        });
    }
};

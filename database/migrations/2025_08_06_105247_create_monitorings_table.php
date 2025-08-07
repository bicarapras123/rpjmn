<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitoringsTable extends Migration
{
    public function up(): void
    {
        Schema::create('monitorings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indikator_id')->constrained('indikators')->onDelete('cascade');
            $table->string('catatan')->nullable();
            $table->year('tahun');
            $table->string('realisasi')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitorings');
    }
}

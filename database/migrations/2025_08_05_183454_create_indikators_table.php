<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('indikators', function (Blueprint $table) {
        $table->id();
        $table->string('indikator');
        $table->string('direktorat');
        $table->string('kl_pelaksana');
        $table->integer('baseline');
        $table->integer('tahun_2019');
        $table->integer('tahun_2020');
        $table->integer('tahun_2021');
        $table->integer('tahun_2022');
        $table->integer('target');
        $table->timestamps();
    });
}

};

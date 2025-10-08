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
        Schema::table('invites', function (Blueprint $table) {
            $table->integer('max_uses')->default(1);   // berapa kali boleh dipakai
            $table->integer('used_count')->default(0); // sudah dipakai berapa kali
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invites', function (Blueprint $table) {
            //
        });
    }
};

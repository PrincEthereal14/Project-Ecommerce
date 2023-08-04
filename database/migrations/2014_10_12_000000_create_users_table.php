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
        Schema::create('users', function (Blueprint $table) {
            // custom users mau punya apa aja
            // ->comment('Nama Pendek') nanti di php my admine ada keterangnya
            $table->id();
            $table->string('name')->comment('Nama Pendek');
            $table->string('email')->unique()->comment('yang simpel');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            // membuat satu tabel sebagai pembeda antara user dan admin
            $table->string('utype')->default('USR')->comment('ADM for Admin and USR for Normal User');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

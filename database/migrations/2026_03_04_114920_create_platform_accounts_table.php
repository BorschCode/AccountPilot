<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_id')->nullable()->constrained('emails')->nullOnDelete();
            $table->string('platform');
            $table->string('login');
            $table->string('username')->nullable();
            $table->string('first_name')->nullable();
            $table->string('geo_region');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_accounts');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('site_url');
            $table->text('description')->nullable();
            $table->boolean('expects_email_confirmation')->default(false);
            $table->unsignedInteger('confirmation_timeout')->default(300);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_templates');
    }
};

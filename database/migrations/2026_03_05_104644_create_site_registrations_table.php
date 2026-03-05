<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_template_id')->constrained()->cascadeOnDelete();
            $table->foreignId('email_id')->constrained('emails')->cascadeOnDelete();
            $table->foreignId('go_login_profile_id')->nullable()->constrained('go_login_profiles')->nullOnDelete();
            $table->string('status')->default('draft');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('phone_number')->nullable();
            $table->json('result_data')->nullable();
            $table->string('confirmation_link')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('queued_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_registrations');
    }
};

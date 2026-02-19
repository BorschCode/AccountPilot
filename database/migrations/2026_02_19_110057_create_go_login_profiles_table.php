<?php

use App\Enums\GoLoginProfileStatus;
use App\Enums\Platform;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('go_login_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('gologin_profile_id')->unique(); // GoLogin Cloud profile ID
            $table->string('name');
            $table->string('platform'); // Platform enum value
            $table->string('proxy_address')->nullable();
            $table->string('status')->default(GoLoginProfileStatus::Active->value);
            $table->unsignedTinyInteger('risk_score')->nullable(); // 0-100
            $table->timestamp('last_score_checked_at')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('go_login_profiles');
    }
};

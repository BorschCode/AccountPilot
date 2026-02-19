<?php

use App\Enums\PostingStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_platform_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_posting_id')->constrained()->cascadeOnDelete();
            $table->foreignId('go_login_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->string('platform'); // Platform enum value
            $table->string('status')->default(PostingStatus::Pending->value);
            $table->string('external_url')->nullable();
            $table->string('screenshot_path')->nullable();
            $table->text('error_message')->nullable();
            $table->unsignedTinyInteger('risk_score_at_posting')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();

            $table->unique(['job_posting_id', 'platform']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_platform_posts');
    }
};

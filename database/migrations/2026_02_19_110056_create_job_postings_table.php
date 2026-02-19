<?php

use App\Enums\EmploymentType;
use App\Enums\JobPostingStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->longText('description');
            $table->string('location');
            $table->string('employment_type')->default(EmploymentType::FullTime->value);
            $table->unsignedInteger('salary_min')->nullable();
            $table->unsignedInteger('salary_max')->nullable();
            $table->string('salary_currency', 3)->default('USD');
            $table->json('platforms'); // array of Platform enum values to post to
            $table->string('status')->default(JobPostingStatus::Draft->value);
            $table->timestamp('queued_at')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};

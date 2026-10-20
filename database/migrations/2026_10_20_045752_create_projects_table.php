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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->json('title');
            $table->json('summary')->nullable();
            $table->json('category')->nullable();
            $table->json('stack')->nullable();
            $table->string('status')->default('live');
            $table->string('repo_url')->nullable();
            $table->string('live_url')->nullable();
            $table->string('image')->nullable();
            $table->string('accent', 7)->nullable();
            $table->string('source')->default('manual');
            $table->unsignedBigInteger('github_id')->nullable()->unique();
            $table->string('language')->nullable();
            $table->unsignedInteger('stars')->default(0);
            $table->boolean('is_private')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_visible')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamp('pushed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

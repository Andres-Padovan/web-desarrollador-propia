<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Turns each service into a package: grouped in tabs, with what it includes and how long it takes.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('group')->default('sites')->after('id');
            $table->json('details')->nullable()->after('description');
            $table->json('includes')->nullable()->after('details');
            $table->json('delivery')->nullable()->after('includes');
            $table->boolean('is_highlighted')->default(false)->after('icon');
            $table->dropColumn('span');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->unsignedTinyInteger('span')->default(1);
            $table->dropColumn(['group', 'details', 'includes', 'delivery', 'is_highlighted']);
        });
    }
};

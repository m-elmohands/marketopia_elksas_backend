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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('type')->default('text');
            $table->timestamps();
        });
        Schema::create('site_setting_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_setting_id')->constrained()->cascadeOnDelete();
            $table->string('locale')->index();
            $table->text('value')->nullable();
            $table->unique(['site_setting_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_setting_translations');
        Schema::dropIfExists('site_settings');
    }
};

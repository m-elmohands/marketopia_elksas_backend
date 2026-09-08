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
        Schema::create('motorcycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('slug')->unique();
            $table->string('manufacturer');
            $table->unsignedSmallInteger('model_year');
            $table->unsignedSmallInteger('engine_cc')->nullable();
            $table->unsignedSmallInteger('power_hp')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->string('currency', 3)->default('EGP');
            $table->boolean('is_featured')->default(false)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('motorcycle_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('motorcycle_id')->constrained()->cascadeOnDelete();
            $table->string('locale')->index();
            $table->string('name');
            $table->text('summary');
            $table->longText('details');
            $table->unique(['motorcycle_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motorcycle_translations');
        Schema::dropIfExists('motorcycles');
    }
};

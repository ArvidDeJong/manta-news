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
        Schema::create('manta_news', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

            // Audit trail fields
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            // Multi-tenancy
            $table->integer('company_id')->nullable();

            // System fields
            $table->string('host')->nullable();
            $table->unsignedBigInteger('pid')->nullable();
            $table->string('locale', 10)->default('en');
            $table->string('author')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('sort')->default(1);

            // Content fields
            $table->string('title')->nullable();
            $table->string('title_2')->nullable();
            $table->string('title_3')->nullable();
            $table->string('slug')->nullable();

            // SEO fields
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();

            // Content management
            $table->text('tags')->nullable();
            $table->text('summary')->nullable();
            $table->longText('excerpt')->nullable();
            $table->longText('content')->nullable();

            // Administration fields
            $table->string('administration')->nullable()->comment('Administration column');
            $table->string('identifier')->nullable()->comment('Identifier column');

            // Flexible data storage
            $table->longText('data')->nullable();

            // Indexes for performance
            $table->index(['active', 'sort']);
            $table->index(['company_id', 'active']);
            $table->index('slug');
            $table->index('locale');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};

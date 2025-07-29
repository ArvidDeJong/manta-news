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
        Schema::create('manta_newscatjoins', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Audit trail fields (no deleted_at for pivot tables typically)
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            // Foreign key relationships
            $table->integer('news_id')->nullable();
            $table->integer('newscat_id')->nullable();

            // Administration fields
            $table->string('administration')->nullable()->comment('Administration column');
            $table->string('identifier')->nullable()->comment('Identifier column');

            // Flexible data storage
            $table->longText('data')->nullable();

            // Indexes for performance
            $table->index(['news_id', 'newscat_id']);
            $table->index('news_id');
            $table->index('newscat_id');

            // Unique constraint to prevent duplicate relationships
            $table->unique(['news_id', 'newscat_id'], 'unique_news_newscat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newscatjoins');
    }
};

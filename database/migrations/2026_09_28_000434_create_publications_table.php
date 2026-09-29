<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_project_id')->nullable()->constrained('research_projects')->nullOnDelete();
            $table->text('title');
            $table->text('authors');
            $table->string('journal_name', 255);
            $table->date('publication_date')->nullable();
            $table->string('doi', 200)->nullable();
            $table->string('pubmed_id', 100)->nullable();
            $table->enum('publication_type', ['journal_article', 'conference_poster', 'conference_paper', 'other']);
            $table->enum('status', ['submitted', 'accepted', 'published'])->default('submitted');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};

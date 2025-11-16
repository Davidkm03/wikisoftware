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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Uploader
            $table->string('original_name');
            $table->string('file_name'); // Stored file name
            $table->string('file_path');
            $table->string('mime_type');
            $table->string('extension');
            $table->unsignedBigInteger('file_size'); // in bytes
            $table->integer('download_count')->default(0);
            $table->timestamps();

            $table->index('document_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};

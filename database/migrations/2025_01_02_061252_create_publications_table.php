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
        Schema::create('publications', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('user_id'); // Foreign key
            $table->string('title');
            $table->string('journal_name');
            $table->date('publication_date')->nullable();
            $table->integer('citations')->default(0);
            $table->string('doi')->unique();
            $table->string('author_name')->nullable();
            $table->string('institution')->nullable();
            $table->string('source')->nullable();
            $table->timestamps();

            // Add the foreign key constraint
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade'); // Cascade delete to remove related publications
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};

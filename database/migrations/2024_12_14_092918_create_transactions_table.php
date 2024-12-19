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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('type');
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('package_form_id')->constrained('package_forms')->cascadeOnDelete();
            $table->date('exp_date')->nullable();
            $table->string('batch')->nullable();
            $table->integer('amount');
            $table->foreignId('donor_id')->constrained('donors')->cascadeOnDelete();
            $table->foreignId('source')->constrained('warehouses')->cascadeOnDelete();
            $table->string('destination')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

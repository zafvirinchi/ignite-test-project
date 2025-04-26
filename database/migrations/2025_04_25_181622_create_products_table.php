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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('revision')->default(0);
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('quantity');
            $table->integer('material_items')->default(0);
            $table->decimal('material_cost', 10, 2)->default(0);
            $table->decimal('waste_percentage', 5, 2)->default(0);
            $table->decimal('waste_amount', 10, 2)->default(0);
            $table->decimal('labour_percentage', 5, 2)->default(0);
            $table->decimal('labour_amount', 10, 2)->default(0);
            $table->decimal('equipment_cost', 10, 2)->default(0);
            $table->decimal('other_percentage', 5, 2)->default(0);
            $table->decimal('other_amount', 10, 2)->default(0);
            $table->decimal('margin_percentage', 5, 2)->default(0);
            $table->decimal('margin_amount', 10, 2)->default(0);
            $table->decimal('sub_total', 10, 2)->default(0);
            $table->decimal('amount', 10, 2)->default(0);
            $table->boolean('delete')->default(false);
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id(); // BIGINT auto increment, primary key
            $table->decimal('balance', 12, 2)->default(0); // Menambahkan nilai awal saldo = 0
            $table->string('title'); // VARCHAR
            $table->decimal('amount', 12, 2); // Decimal(12,2)
            $table->enum('category', [ // ENUM
                'food',
                'transportation',
                'bills',
                'entertainment',
                'health',
                'education',
                'others',
            ]);
            $table->enum('type', ['income', 'expense']); // ENUM
            $table->text('description')->nullable(); // TEXT nullable
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};

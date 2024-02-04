<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->timestamps();
        });

        $product_data = [
            [
                'name' => 'A',
                'price' => 30,
            ],
            [
                'name' => 'B',
                'price' => 20,
            ],
            [
                'name' => 'C',
                'price' => 50,
            ],
            [
                'name' => 'D',
                'price' => 15,
            ],
        ];

        DB::table('products')->insert($product_data);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

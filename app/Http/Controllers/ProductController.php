<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProduct(): JsonResponse
    {
        $products = Product::select('id','name','price')->get();

        if($products->isEmpty()) {
            return error('No products found');
        }

        return success('Products fetched successfully', $products);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getTemporaryUser()
    {
        // getting hardcode user directly from DB
        return User::find(1);
    }

    public function addItemInCart(Request $request, $productId, $quantity): JsonResponse
    {
        $product = Product::find($productId);

        // check product exists or not in product table
        if(!$product) {
            return error('Product not found');
        }

        $user = $this->getTemporaryUser();

        // check item exists or not in item array
        $existingItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingItem) {
            // add the quantity
            $existingItem->quantity += $quantity;
            $existingItem->save();
        } else {
            // save the item in cart table
            CartItem::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        return success('Items added successfully');
    }

    public function getCart(Request $request): JsonResponse
    {
        $user = $this->getTemporaryUser();
        $cartItems = CartItem::select('id','user_id','product_id','quantity')->where('user_id', $user->id)->with(['product_details:id,name,price'])->get();

        $cartData = $this->calculateTotal($cartItems);
        return success('cart items fetched successfully', $cartData);
    }

    public function calculateTotal($cartItems): array
    {
        $totalPrice = 0;
        $totalDiscount = 0;
        $itemsWithDetails = [];

        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product_details;
            $quantity = $cartItem->quantity;

            //1: If 3 of Item A is purchased, the price of all three is Rs 75
            if ($product->name === 'A' && $quantity >= 3) {
                $setsOfThree = floor($quantity / 3);
                 $totalDiscount += $setsOfThree * ($product->price * 3 - 75);
            }

            //2: If 2 of Item B is purchased, the price of both is Rs 35
            if ($product->name === 'B' && $quantity >= 2) {
                $setsOfTwo = floor($quantity / 2);
                 $totalDiscount += $setsOfTwo * ($product->price * 2 - 35);
            }

            $totalPrice += $product->price * $quantity;

            $itemsWithDetails[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'individual_price' => $product->price,
                'discount_applied' => $totalDiscount,
                'total_price' => $totalPrice,
            ];

        }

        //3: If the total basket price is over Rs 150, the basket receives an additional discount of Rs 20
        if ($totalPrice > 150) {
            $totalDiscount += 20;
        }

        $totalPriceWithDiscount = $totalPrice - $totalDiscount;

        return [
            'items' => $itemsWithDetails,
            'actual_total_price' => $totalPrice,
            'total_price_with_discount' => $totalPriceWithDiscount,
            'total_discount' => $totalDiscount,
        ];
    }

    public function deleteItemInCart($productId): JsonResponse
    {
        $product = Product::find($productId);

        // check product exists or not in product table
        if(!$product) {
            return error('Product not found');
        }

        $user = $this->getTemporaryUser();
        $cartItems = CartItem::where('user_id', $user->id)->where('product_id', $productId)->get();

        if($cartItems->isEmpty()) {
            return error('There is no items in your cart with product-id: '.$productId);
        }

        foreach ($cartItems as $cartItem) {
            $cartItem->delete();
        }

        return success('Item deleted from your cart successfully');
    }
}

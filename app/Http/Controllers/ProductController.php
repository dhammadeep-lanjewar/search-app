<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ProductController extends Controller
{
    
    public function index() {
        $cachedProduct = Redis::get('products');
        if(isset($cachedProduct)) {
            $product = json_decode($cachedProduct, False);
            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from redis',
                'data' => $product,
            ]);
        }else {
            $product = Product::get();
            Redis::set('products', json_encode($product));     
            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from database',
                'data' => $product,
            ]);
        }
    }


    public function delete() {

        // Product::findOrFail($id)->delete();
        Redis::del('products');
      
        return response()->json([
            'status_code' => 201,
            'message' => 'Products deleted'
        ]);
      }
}

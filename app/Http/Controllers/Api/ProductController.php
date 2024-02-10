<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    //index api
    public function index() {
        $products = Product::all();
        return response()->json([
            'message' => 'success',
            'data' => $products,
        ], 200);
    }
}

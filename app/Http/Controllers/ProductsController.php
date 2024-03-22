<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductsController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $products = Product::query()->latest();

        if ($request->filled('amazon_id')) {
            $products->where('amazon_id', '=', $request->amazon_id);
        }

        $products = $products->paginate(20);

        return view('products.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::query()->with(['offers.seller'])->where('amazon_id', $id)->firstOrFail();
        // return ($product);

        return view('products.show', compact('product'));
    }
}

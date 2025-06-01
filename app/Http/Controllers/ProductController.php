<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    //
    public function ProductCreate(Request $request)
    {
        $user_id = $request->header('user_id');
        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = $user_id . '_' . time() . '_' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('uploads', $imageName, 'public');

            // return view('pages.product.productPage');

        }
        $product = Product::create([
            'user_id'     => $request->header('user_id'),
            'name'        => $request->input('name'),
            
            'img_url'       => $imagePath ?? null,
            'price'       => $request->input('price'),
            'unit'        => $request->input('unit'),
            'category_id' => $request->input('category_id'),
        ]);
        return response()->json([
            'status'  => 'success',
            'message' => 'Product created successfully',
            'data'    => $product,
        ], 201);

        // return view('pages.product.productPage');
    }

    public function ProductList(Request $request)
    {
        
        $product = Product::where('user_id', $request->header('user_id'))->get();
        if ($product) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Product fetched successfully',
                'data'    => $product,
            ], 200);
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'Product not found',
            ], 404);
        }
    }

    public function ProductById(Request $request)
    {
        $productId = $request->input('id');
        $product = Product::where('id', $productId)->where('user_id', $request->header('user_id'))->first();
        if ($product) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Product fetched successfully',
                'data'    => $product,
            ], 200);
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'Product not found',
            ], 404);
        }
    }
    public function ProductUpdate(Request $request)
    {
        $productId = $request->input('id');
        $product = Product::where('id', $productId)->where('user_id', $request->header('user_id'))->first();

        if($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = $product->user_id . '_' . time() . '_' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('uploads', $imageName, 'public');
            Storage::disk('public')->delete($product->img_url);
        }else{
            $imagePath =$product->img_url;
        }
        // Check if the product exists

        if ($product) {
            $product->update([
                'name'        => $request->input('name') ?? $product->name,
                'img_url'     => $imagePath ,
                'price'       => $request->input('price') ?? $product->price,
                'unit'        => $request->input('unit') ?? $product->unit,
                'category_id' => $request->input('category_id') ?? $product->category_id,
            ]);
            return response()->json([
                'status'  => 'success',
                'message' => 'Product updated successfully',
            ], 200);
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'Product not found',
            ], 404);
        }
    }

    public function ProductDelete(Request $request)
    {
        $productId = $request->input('id');
        $product = Product::where('id', $productId)->where('user_id', $request->header('user_id'))->first();

        if($product->img_url) {
            // Delete the image file if it exists
           Storage::disk('public')->delete($product->img_url);
        }
        if ($product) {
            $product->delete();
            return response()->json([
                'status'  => 'success',
                'message' => 'Product deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'Product not found',
            ], 404);
        }
    }

}
<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function categoryList(Request $request)
    {

        // Here you can fetch the categories from the database and return them to the view
        $user_id    = $request->header('user_id');
        $categories = Category::where('user_id', $user_id)->get();
        return response()->json([
            'status'  => 'success',
            'message' => 'Category List fetched successfully',
            'data'    => $categories,
        ], 200);
        // return view('pages.dashboard.category');
    }

    public function CategoryCreate(Request $request)
    {
        // Here you can create a new category in the database
        $category = Category::create([
            'user_id' => $request->header('user_id'),
            'name'    => $request->name,

        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Category created successfully',
            'data'    => $category,
        ], 201);
    }

    public function CategoryDelete(Request $request)
    {
        $category_id = $request->input('id');
        $user_id     = $request->header('user_id');
        return response()->json([
            "data"    => Category::where('id', $category_id)->where('user_id', $user_id)->delete(),
            'status'  => 'success',
            'message' => 'Category deleted successfully',
        ], 200);
    }

    public function CategoryUpdate(Request $request){
        $category_id = $request->input('id');
        $user_id     = $request->header('user_id');

        // Find the category by ID and user ID
        $category = Category::where('id', $category_id)->where('user_id', $user_id)->first();

        if (!$category) {
            return response()->json([
                'status'  => 'failed',
                'message' => 'Category not found',
            ], 404);
        }

        // Update the category
       Category::where('id', $category_id)->where('user_id', $user_id)->update([
           'name' => $request->input('name', $category->name),
       ]);
        // Fetch the updated category
        $category = Category::where('id', $category_id)->where('user_id', $user_id)->first();

        return response()->json([
            'status'  => 'success',
            'message' => 'Category updated successfully',
            'data'    => $category,
        ], 200);
    }

    public function CategoryById(Request $request)
    {
        $category_id = $request->input('id');
        $user_id     = $request->header('user_id');
        $category = Category::where('id', $category_id)->where('user_id', $user_id)->first();
        if (!$category) {
            return response()->json([
                'status'  => 'failed',
                'message' => 'Category not found',
            ], 404);
        }
        return response()->json([
            'status'  => 'success',
            'message' => 'Category fetched successfully',
            'data'    => $category,
        ], 200);
    }

}

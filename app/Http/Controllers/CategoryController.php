<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    //
    function categoryList(Request $request){
    
        // Here you can fetch the categories from the database and return them to the view  
        $user_id=$request->header('user_id');
        $categories = Category::where('user_id', $user_id)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Category List fetched successfully',
            'data' => $categories
        ], 200);
        // return view('pages.dashboard.category');
    }

    
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    //index
    public function index(Request $request)
    {
        //get all categories with pagination
        $categories = Category::paginate(10);
        return view('pages.categories.index', compact('categories'));
    }

    //create
    public function create() {
        return view('pages.categories.create');
    }

    //store
    public function store(Request $request) {
        $request->validate([
            'name'=> 'required',
            'description'=> 'required',
            'image'=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // create category
        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;


        //save image
        if($request->hasFile('image')) {
        $image = $request->file('image');
        $image->storeAs('public/categories', $category->id . '.' . $image->getClientOriginalExtension());
        $category->image = $category->id . '.' . $image->getClientOriginalExtension();
        $category->save();
        }

        return redirect()->route('categories.index')->with('success', 'Category created successfully');
    }

    //show
    public function show($id) {
        $category = Category::find($id);
        return view('pages.categories.show', compact('category'));
    }

    //edit
    public function edit($id) {
        $category = Category::find($id);
        return view('pages.categories.edit', compact('category'));
    }

    //update
    public function update(Request $request, $id) {
        $request->validate([
            'name'=> 'required',
            // 'description'=> 'required',
            // 'image'=> 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // update category
        $category = Category::find($id);
        $category->name = $request->name;
        $category->description = $request->description;
        if($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/categories', $category->id . '.' . $image->getClientOriginalExtension());
            $category->image = $category->id . '.' . $image->getClientOriginalExtension();
            $category->save();
        }


        return redirect()->route('categories.index')->with('success', 'Category updated successfully');
    }

    //destroy
    public function destroy($id) {
        // delete category
        $category = Category::find($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully');
    }
}

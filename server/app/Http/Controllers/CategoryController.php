<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index($status = null) {
    return response(['categories' => Category::status($status)->latest()->get()]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    $category = $request->validate([
      'name' => 'required | min:3 | unique:categories',
    ]);

    return response(['category' => auth()->user()->categories()->create($category)]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Category  $category
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Category $category) {
    $newCategory = $request->validate([
      'name' => 'required|unique:categories,name,' . $category->id,
    ]);
    $category->update($newCategory);
    $category->touch();
    return $category;
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Category  $category
   * @return \Illuminate\Http\Response
   */
  public function destroy($id, $force = null) {
    $category = Category::withTrashed()->find($id);
    if ($category) {
      if ($force == 1) {
        $category->forceDelete();
      } else {
        $category->delete();
      }
      return response(['category' => $category]);
    }
    return response(['message' => 'Category not found'], 404);
  }

  public function restore($id) {
    $category = Category::withTrashed()->find($id);
    if ($category) {
      $category->restore();
      return response(['category' => $category]);
    }
    return response(['message' => 'Category not found'], 404);
  }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
  /**
   * Display a listing of the available categories.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $categories = Category::get();
    $response['status'] = 'success';
    $response['categories'] = $categories;
    return response()->json($response, Response::HTTP_OK);
  }

  /**
   * store new category.
   *
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {

    $this->validate($request, [
      'cat_name' => 'required|regex:/^[\s\w-]*$/',
      'cat_icon' => 'required|image|mimes:png,jpg,jpeg,svg|max:1000',
    ]);
    try {
      $cat = new Category();
      $cat->name = $request->cat_name;
      $cat->save();
      $cat->refresh();
      if ($request->hasFile('cat_icon')) {
        $cat_icon = $request->file('cat_icon');
        $img_ext = $cat_icon->getClientOriginalExtension();
        $img_name = $cat->slug . strtolower(Str::random(3)) . "." . $img_ext;
        $cat_icon->move(public_path('images/categories/'), $img_name);
        if (File::exists(public_path('images/categories/' . $img_name))) {
          $cat->image = $img_name;
          $cat->update();
        }
      }
      $response['status'] = 'success';
      $response['message'] = 'Category has been Created';
      $response['category'] = $cat;
      return response()->json($response, Response::HTTP_OK);
    } catch (\Exception $e) {
      $response['status'] = 'error';
      $response['message'] = $e->getMessage();
      return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }


  /**
   * Update the specified category.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Category  $category
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $category_slug)
  {
    $this->validate($request, [
      'cat_name' => 'sometimes|nullable|regex:/^[\s\w-]*$/',
      'cat_icon' => 'sometimes|nullable|image|mimes:png,jpg,svg,jpeg|max:2024',
    ]);
    if (Category::whereSlug($category_slug)->exists()) {
      try {
        $cat = Category::whereSlug($category_slug)->first();
        if ($request->has('cat_name')) {
          $cat->name = $request->cat_name;
        }
        $cat->update();
        if ($category_slug != $cat->slug && $cat->image != null) {
          $new_image_name = substr_replace($cat->image, $cat->slug . "-" . strtolower(Str::random(3)), 0, strrpos($cat->image, '.'));
          $old_img = public_path(sprintf("images/categories/", $category_slug, $cat->image));
          $new_img = public_path(sprintf("images/categories/", $cat->slug, $new_image_name));
          if (File::exists($old_img)) {
            File::move($old_img, $new_img);
            $cat->image = $new_image_name;
            $cat->update();
          }
        }
        if ($request->hasFile('cat_icon')) {
          $cat_icon = $request->file('cat_icon');
          $img_ext = $cat_icon->getClientOriginalExtension();
          $img_name = $cat->slug . strtolower(Str::random(3)) . "." . $img_ext;
          $cat_icon->move(public_path('images/categories/'), $img_name);
          if (File::exists(public_path('images/categories/' . $img_name))) {
            $cat->image = $img_name;
            $cat->update();
          }
        }
        $response['status'] = 'success';
        $response['message'] = 'Category has been Updated';
        $response['category'] = $cat;
        return response()->json($response, Response::HTTP_OK);
      } catch (\Exception $e) {
        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
        return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
      }
    } else {
      $response['status'] = 'error';
      $response['message'] = 'No such Category';
      return response()->json($response, Response::HTTP_OK);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Category  $category
   * @return \Illuminate\Http\Response
   */
  public function destroy($category_slug)
  {
    if (Category::whereSlug($category_slug)->exists()) {
      try {
        $cat = Category::whereSlug($category_slug)->first();
        $cat->products()->update(['category_id' => null]);
        if ($cat->image != null) {
          $img_path = public_path(sprintf("images/categories/" . $cat->image));
          if (File::exists($img_path)) {
            File::delete($img_path);
          }
        }
        $cat->delete();
        $response['status'] = 'success';
        $response['message'] = "Category has been Deleted";
        $response['category'] = $cat;
        return response()->json($response, Response::HTTP_OK);
      } catch (\Exception $e) {
        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
        return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
      }
    } else {
      $response['status'] = 'error';
      $response['message'] = 'No such Category';
      return response()->json($response, Response::HTTP_OK);
    }
  }
}

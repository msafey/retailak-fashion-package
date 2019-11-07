<?php

namespace App\Http\Controllers\Category;

use App\Models\Categories;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\utilitiesController;
use App\Http\Requests\CategoryRequest;
use App\Models\User;
use Auth;
use DB;
use File;
use Illuminate\Support\Facades\Input;
use Image;
use Yajra\Datatables\Datatables;

class CategoryController extends utilitiesController
{

    protected $imageController;

    public function __construct()
    {
        $this->imageController = new ImageController();
    }

    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('categories'))
        {
            return view('admin.un-authorized');
        }

        return view('admin/categories/index');
    }

    public function categoriesList()
    {
        if (!Auth::guard('admin_user')->user()->can('categories'))
        {
            return view('admin.un-authorized');
        }
        $active = isset($_GET['active']) ? $_GET['active'] : 0;
        $inactive = isset($_GET['inactive']) ? $_GET['inactive'] : 0;
        if ($active == 1 && $inactive == 1) {
            $categories = Categories::orderBy('sorting_no', 'ASC')->get();
        } elseif ($active == 1 && $inactive == 0) {
            $categories = Categories::where('status', 1)->orderBy('sorting_no', 'ASC')->get();
        } elseif ($active == 0 && $inactive == 1) {
            $categories = Categories::where('status', 0)->orderBy('sorting_no', 'ASC')->get();
        } else {
            $categories = Categories::orderBy('sorting_no', 'ASC')->get();
        }
        foreach ($categories as $cat) {
            $image = $cat->images()->orderBy('id', 'asc')->first();
            $cat['image'] = $image ? $image->image : 'default.jpg';
            $category = Categories::where('id', $cat['parent_item_group'])->first();
            $cat['parent_item_group'] = $category ? $category->name : '----';
        }
        return Datatables::of($categories)->make(true);
    }

    public function create()
    {
        if (!Auth::guard('admin_user')->user()->can('categories'))
        {
            return view('admin.un-authorized');
        }
        $categories = Categories::where('status', 1)->get();
        $categories = getCategories($categories);
        $parentCategories = Categories::where('parent_item_group', 0)->where('status', 1)->get();

        return view('admin/categories/add', compact('categories', 'parentCategories'));
    }

    public function Status($id)
    {
        if (!Auth::guard('admin_user')->user()->can('categories'))
        {
            return view('admin.un-authorized');
        }
        $user = Auth::guard('admin_user')->user();
        $status = Categories::where('id', $id)->first();
        if (!$status->name) {
            $status->name = '';
            $status->save();
        }
        if ($status->status == 0) {
            Categories::where('id', $id)->update(['status' => '1']);
            $status->adjustments()->attach($user->id, ['key' => "Category", 'action' => "Category Activated", 'content_name' => $status->name]);
            return redirect('admin/categories')->with('Category  Activated Successfully');
        } else {
            Categories::where('id', $id)->update(['status' => '0']);
            $status->adjustments()->attach($user->id, ['key' => "Category", 'action' => "Category Deactivated", 'content_name' => $status->name]);
            return redirect('admin/categories')->with('success', 'Category Disabled Successfully');
        }
    }

    public function getLastItemCode($num)
    {
        $latest_item_code = Categories::whereNotNull('item_code')->orderBy('id', 'desc')->select('item_code')->first();
        if ($latest_item_code) {
            $num = (int)$latest_item_code->item_code;
            // $num = $this->updateCategoriesItemCode($num);
            $num += 1;
            $num = sprintf("%02d", $num);
        } else {
            $num = null;
        }
        return $num;
    }

    public function updateCategoriesItemCode($num)
    {
        if ($num == 99) {
            $categories = Categories::whereNotNull('item_code')->count();
            if ($categories != 99) {
                $categories = Categories::all();
                $number = 1;
                foreach ($categories as $cat) {
                    $cat->update(['item_code' => sprintf("%02d", $number)]);
                    $number++;
                }
            }
            $num = $number;
        }
        return $num;
    }

    public function store(CategoryRequest $request)
    {
        if (!Auth::guard('admin_user')->user()->can('categories'))
        {
            return view('admin.un-authorized');
        }

        return DB::transaction(function () use ($request) {
            if (!$request->hasFile('image')) {
                return redirect()->back()->withErrors('Image Required');
            }
            $num = null;
            $num = $this->getLastItemCode($num);
            // check if num == 99 to recount the
            // $num = $this->updateCategoriesItemCode($num);
            if (is_null($request->parent_item_group)) {
                $request->parent_item_group = 0;
            }
            $sortingNoArray = Categories::pluck('sorting_no');
            if (is_array($sortingNoArray) && count($sortingNoArray) > 0)
                $sortno = (int)max($sortingNoArray) + 1;
            else
                $sortno = 10000000;

            $category = Categories::create(['name' => $request->name, 'name_en' => $request->name_en, 'parent_item_group' => $request->parent_item_group, 'item_code' => $num, 'sorting_no' => $sortno]);
            if ($request->hasFile('image')) {
                $category_image = $request->image;
                $file_name = $this->imageController->saveImage($category_image, "categories");
                $category->categoryImage($file_name);
            }
            $user = Auth::guard('admin_user')->user();
            Categories::where('id', $request->parent_item_group)->update(['has_sub' => 1]);
            $category->adjustments()->attach($user->id, ['key' => "Category", 'action' => "Added", 'content_name' => $category->name]);
            return redirect('admin/categories')->with('success', 'Category Created Successfully');

        });
    }

    public function manageSubCategories($id)
    {
        if (!Auth::guard('admin_user')->user()->can('categories'))
        {
            return view('admin.un-authorized');
        }
        $subcategories = Categories::where('parent_item_group', $id)->where('status', 1)->get();
        return view('admin/categories/subcategories', compact('subcategories'));
    }

    public function reOrderSubCategories()
    {
        if (!Auth::guard('admin_user')->user()->can('categories'))
        {
            return view('admin.un-authorized');
        }
        $rowID = Input::get('rowID');
        $rowIndex = Input::get('rowIndex');
        $parent = Input::get('parent');

        $categories = Categories::where('id', $rowID)->where('status', 1)->get();
        foreach ($categories as $value) {
            DB::table('categories')->where('id', '=', $rowID)->update(array('sorting_no' => $rowIndex));
        }
        $categories = Categories::where('parent_item_group', $parent)->orderBy('sorting_no', 'ASC')->where('status', 1)->get();
        if (count($categories) > 0) {
            return $categories;
        }
        return false;
    }

    public function edit($id)
    {
        if (!Auth::guard('admin_user')->user()->can('categories'))
        {
            return view('admin.un-authorized');
        }

        $categories = Categories::where('id', '!=', $id)->where('status', 1)->get();
        $categories = getCategories($categories);
        $parentCategories = Categories::where('parent_item_group', 0)->where('status', 1)->get();
        $category = Categories::findOrFail($id);
        $category_image = $category->images()->orderBy('id')->first();
        return view('/admin/categories/edit', compact('category', 'category_image', 'categories', 'parentCategories'));
    }

    public function update(CategoryRequest $request, $id)
    {
        if (!Auth::guard('admin_user')->user()->can('categories'))
        {
            return view('admin.un-authorized');
        }

        return DB::transaction(function () use ($request, $id) {
            $category = Categories::find($id);
            $category->update(['name' => $request->name, 'name_en' => $request->name_en, 'parent_item_group' => $request->parent_item_group]);
            if ($request->hasFile('image')) {
                $category_image = $category->images()->first();
                if ($category_image) {
                    $imgpath = public_path('imgs/categories/' . $category_image->image);
                    File::delete($imgpath);
                    $imgthubmailpath = public_path('imgs/categories/thumb/' . $category_image->image);
                    File::delete($imgthubmailpath);
                    $category_image->delete();
                }
                $category_image = $request->image;
                $image = $this->imageController->saveImage($category_image, "categories");
                $category->categoryImage($image);
            }

            Categories::where('id', $request->parent_item_group)->update(['has_sub' => 1]);
            $user = Auth::guard('admin_user')->user();
            $category->adjustments()->attach($user->id, ['key' => "Category", 'action' => "Edited", 'content_name' => $category->name]);
            return redirect($category->path())->with('success', 'Category Updated Successfully');
        });
    }

    public function getOrderPage()
    {
        if (!Auth::guard('admin_user')->user()->can('categories'))
        {
            return view('admin.un-authorized');
        }

        $data = Categories::orderBy('sorting_no', 'ASC')->where('has_sub', 1)->where('status', 1)->get();
        return view('admin.categories.order', compact('data'));
    }

    public function reOrder()
    {
        if (!Auth::guard('admin_user')->user()->can('categories'))
        {
            return view('admin.un-authorized');
        }
        $rowID = Input::get('rowID');
        $rowIndex = Input::get('rowIndex');
        $categories = Categories::where('id', $rowID)->where('status', 1)->get();
        foreach ($categories as $value) {
            DB::table('categories')->where('id', '=', $rowID)->update(array('sorting_no' => $rowIndex));
        }
        $categories = Categories::orderBy('sorting_no', 'ASC')->get();
        if (count($categories) > 0) {
            return $categories;
        }
        return false;
    }

}

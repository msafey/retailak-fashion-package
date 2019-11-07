<?php

namespace App\Http\Controllers\Brands;

use App\Models\Brands;
use App\Models\Company;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use App\Http\Requests\AddBrandRequest;
use DB;
use Auth;
use File;
use Illuminate\Http\Request;
use Image;
use Yajra\Datatables\Datatables;


class BrandsController extends Controller
{


    protected $imageController;

    public function __construct()
    {
        $this->imageController = new ImageController();

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('brands'))
        {
            return view('admin.un-authorized');
        }

        return view('admin.brands.index');
    }


    public function brandsList()
    {
        if (!Auth::guard('admin_user')->user()->can('brands'))
        {
            return view('admin.un-authorized');
        }

        $brands = Brands::orderBy('id', 'ASC')->get();

        foreach ($brands as $brand) {
            $image = $brand->images()->orderBy('id', 'asc')->first();
            if ($image) {
                $brand['image'] = $image->image;
            } else {
                $brand['image'] = 'default.jpg';
            }

        }

        return Datatables::of($brands)->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::guard('admin_user')->user()->can('brands'))
        {
            return view('admin.un-authorized');
        }

        $companies = Company::where('status', 1)->get();
        return view('admin.brands.add', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddBrandRequest $request)
    {
        if (!Auth::guard('admin_user')->user()->can('brands'))
        {
            return view('admin.un-authorized');
        }

        if (!$request->hasFile('logo')) {
            return redirect()->back()->withErrors('Image Required');
        }
        $brand = new Brands;
        $brand->name = $request->name;
        $brand->name_en = $request->name_en;
        $brand->description = $request->description;
        $brand->description_en = $request->description_en;
        $brand->company_id = $request->company_id;
        $brand->save();
        if ($request->hasFile('logo')) {
            $brand_image = $request->logo;

            $file_name = $this->imageController->saveImage($brand_image, "brands");
            $brand->brandImage($file_name);

        }
        $user = Auth::guard('admin_user')->user();

        $brand->adjustments()->attach($user->id, ['key' => "Brand", 'action' => "Added", 'content_name' => $brand->name]);


        return redirect('admin/brands')->with('success', 'Brand Created Successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::guard('admin_user')->user()->can('brands'))
        {
            return view('admin.un-authorized');
        }

        $companies = Company::where('status', 1)->get();
        $brand = Brands::findOrFail($id);
        $brand_image = $brand->images()->orderBy('id')->first();
        return view('/admin/brands/edit', compact('brand', 'brand_image', 'companies'));
    }

    public function update(AddBrandRequest $request, $id)
    {
        if (!Auth::guard('admin_user')->user()->can('brands'))
        {
            return view('admin.un-authorized');
        }

        return DB::transaction(function () use ($request, $id) {

            $brand = Brands::find($id);
            $brand->name = $request->name;
            $brand->name_en = $request->name_en;
            $brand->description = $request->description;
            $brand->description_en = $request->description_en;
            $brand->company_id = $request->company_id;
            $brand->save();


            if ($request->hasFile('logo')) {
                $brand_image = $brand->images()->first();
                if ($brand_image) {
                    $imgpath = public_path('imgs/brands/' . $brand_image->image);
                    File::delete($imgpath);
                    $imgthubmailpath = public_path('imgs/brands/thumb/' . $brand_image->image);
                    File::delete($imgthubmailpath);
                    $brand_image->delete();
                }
                $brand_image = $request->logo;
                $image = $this->imageController->saveImage($brand_image, "brands");
                $brand->brandImage($image);

            }
            $user = Auth::guard('admin_user')->user();

            $brand->adjustments()->attach($user->id, ['key' => "Brand", 'action' => "Edited", 'content_name' => $brand->name]);


            return redirect($brand->path())->with('success', 'Brand Updated Successfully');
        });

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        if (!Auth::guard('admin_user')->user()->can('brands'))
        {
            return view('admin.un-authorized');
        }

        $brand = Brands::findOrFail($id);

        if ($brand) {
            $brand_image = $brand->images()->first();
            if ($brand_image) {

                $imgpath = public_path('imgs/brands/' . $brand_image->image);
                File::delete($imgpath);
                $imgthubmailpath = public_path('imgs/brands/thumb/' . $brand_image->image);
                File::delete($imgthubmailpath);
                $brand_image->delete();
            }

            $user = Auth::guard('admin_user')->user();

            $brand->adjustments()->attach($user->id, ['key' => "Brand", 'action' => "Deleted", 'content_name' => $brand->name]);

            $brand->delete();


            return 'true';
        } else {
            return 'false';
        }

        // return redirect($brand->path())->with('success','Brand Deleted Successfully');


    }
}

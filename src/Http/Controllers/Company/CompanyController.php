<?php

namespace App\Http\Controllers\Company;

use App\Models\Company;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use App\Models\Image;
use DB;
use File;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class CompanyController extends Controller
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
        if (!Auth::guard('admin_user')->user()->can('companies'))
        {
            return view('admin.un-authorized');
        }

        return view('admin.companies.index');
    }


    public function companiesList()
    {
        if (!Auth::guard('admin_user')->user()->can('companies'))
        {
            return view('admin.un-authorized');
        }
        $companies = Company::all();
        return Datatables::of($companies)->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::guard('admin_user')->user()->can('companies'))
        {
            return view('admin.un-authorized');
        }

        return view('admin.companies.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('companies'))
        {
            return view('admin.un-authorized');
        }

        $company = new Company;
        $company->status = 1;
        $company->name = $request->name;
        $company->name_en = $request->name_en;
        if ($request->hasFile('logo')) {
            $company_logo = $request->logo;
            $file_name = $this->imageController->saveImage($company_logo, "companies");
            $company->logo = $file_name;
        }
        // $company->location = $request->location;
        $company->description = $request->description;
        $company->description_en = $request->description_en;
        $company->save();
        return redirect($company->path())->with('success', 'Company Created Successfully');
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
        if (!Auth::guard('admin_user')->user()->can('companies'))
        {
            return view('admin.un-authorized');
        }

        $company = Company::find($id);
        return view('admin.companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Auth::guard('admin_user')->user()->can('companies'))
        {
            return view('admin.un-authorized');
        }
        $company = Company::find($id);
        if ($request->hasFile('logo')) {
            // dd($company->logo);
            // $company_logo = Image::find($id);
            $company->update($request->all());

            if ($company && $company->logo) {
                $company_logo = $company->logo;

                $imgpath = public_path('imgs/companies/' . $company_logo);
                File::delete($imgpath);
                $imgthubmailpath = public_path('imgs/companies/thumb/' . $company_logo);
                File::delete($imgthubmailpath);
            }
            $company_logo = $request->logo;
            $image = $this->imageController->saveImage($company_logo, "companies");
            // dd($image);
            $company->logo = $image;
            $company->save();
        }

        return redirect($company->path())->with('success', 'Company Updated Successfully');
    }


    public function Status($id)
    {
        if (!Auth::guard('admin_user')->user()->can('companies'))
        {
            return view('admin.un-authorized');
        }

        $status = Company::where('id', $id)->select('status')->first();
        if ($status->status == 0) {
            Company::where('id', $id)->update(['status' => '1']);
            return redirect('admin/companies')->with('Company Activated Successfully');
        } else {
            Company::where('id', $id)->update(['status' => '0']);
            return redirect('admin/companies')->with('success', 'Company  De-Activated Successfully');

        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

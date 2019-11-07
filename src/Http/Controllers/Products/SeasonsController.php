<?php

namespace App\Http\Controllers\Products;

use App\Models\Seasons;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use File;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;


class SeasonsController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.seasons.index');
    }


    public function seasonsList() {
        $seasons = Seasons::orderBy('id','ASC')->get();
        // dd($seasons);
        return Datatables::of($seasons)->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.seasons.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $season = new Seasons;
        $season->name = $request->name;
        $season->name_en=$request->name_en;
        $season->description=$request->description;
        $season->description_en=$request->description_en;
        $season->save();
        $user = Auth::guard('admin_user')->user();
        $season->adjustments()->attach($user->id, ['key' =>"SEASONS", 'action' =>"Added",'content_name'=>$season->name_en]);

        return redirect('admin/seasons')->with('success','Season Created Successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
            $season = Seasons::findOrFail($id);
            return view('/admin/seasons/edit', compact('season'));
        }

        public function update(Request $request, $id) {
        return DB::transaction(function () use ($request,$id){

            $season = Seasons::find($id);
            $season->update(['name'=>$request->name,'name_en'=>$request->name_en,'description'=>$request->description,'description_en'=>$request->description_en]);
            $user = Auth::guard('admin_user')->user();
            $season->adjustments()->attach($user->id, ['key' =>"SEASONS", 'action' =>"Edited",'content_name'=>$season->name_en]);



            return redirect($season->path())->with('success','Season Updated Successfully');
        });

        }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $season = Seasons::findOrFail($id);

        if($season){
            $user = Auth::guard('admin_user')->user();
            $season->adjustments()->attach($user->id, ['key' =>"SEASONS", 'action' =>"Deleted",'content_name'=>$season->name_en]);
            $season->delete();
            return 'true';
        }else{
            return 'false';
        }

        // return redirect($brand->path())->with('success','Brand Deleted Successfully');


    }
}

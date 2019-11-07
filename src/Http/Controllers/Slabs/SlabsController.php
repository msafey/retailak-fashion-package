<?php

namespace App\Http\Controllers\Slabs;

use App\Models\Slabs;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use App\Http\Requests\SlabsRequest;
use DB;
use Auth;
use File;
use Illuminate\Http\Request;
use Image;
use Response;
use Yajra\Datatables\Datatables;


class SlabsController extends Controller
{

    public function index()
    {
        return view('admin.slabs.index');
    }


    public function slabsList() {
        $slabs = Slabs::orderBy('id','ASC')->get();
         foreach($slabs as $slab){
            if($slab->discount_type =='percentage'){
                $slab->discount_rate = $slab->discount_rate . ' %';
            }else{
                $slab->discount_rate = $slab->discount_rate . ' Pounds';
            }

         }

        return Datatables::of($slabs)->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $companies = Company::where('status',1)->get();
        return view('admin.slabs.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SlabsRequest $request)
    {
        // return $request->all();
        $slab = Slabs::create($request->all());
        $user = Auth::guard('admin_user')->user();
        $slab->adjustments()->attach($user->id, ['key' =>"Slabs", 'action' =>"Added",'content_name'=>$slab->slab_name]);

        return redirect('admin/slabs')->with('success','Slab Created Successfully');

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
    public function edit($id)
    {
            $slab = Slabs::findOrFail($id);
            return view('/admin/slabs/edit', compact('slab'));
    }

        public function update(SlabsRequest $request, $id) {
        return DB::transaction(function () use ($request,$id){

            $slab = Slabs::findOrFail($id);
            if ($slab) {
                $slab = $slab->update($request->all());
            }
            $slab =  Slabs::findOrFail($id);

            $user = Auth::guard('admin_user')->user();
            $slab->adjustments()->attach($user->id, ['key' =>"Slabs", 'action' =>"Edited",'content_name'=>$slab->slab_name]);



            return redirect($slab->path())->with('success','Slab Updated Successfully');
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
        $slab = Slabs::findOrFail($id);

        if($slab){


            $user = Auth::guard('admin_user')->user();
            $slab->adjustments()->attach($user->id, ['key' =>"Slabs", 'action' =>"Deleted",'content_name'=>$slab->slab_name]);
            $slab->delete();
            return 'true';
        }else{
            return 'false';
        }

        // return redirect($brand->path())->with('success','Brand Deleted Successfully');


    }




    public function getSlabs(){
        $slabs = Slabs::all();
        if(count($slabs) <= 0){
            $slabs=[];
        }
         return Response::json($slabs);
    }
}

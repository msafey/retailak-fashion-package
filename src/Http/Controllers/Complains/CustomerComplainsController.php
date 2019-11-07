<?php

namespace App\Http\Controllers\Complains;

use App\Models\CustomerComplains;
use App\Http\Controllers\Controller;
use App\Http\Requests\ComplainsRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Auth;
class CustomerComplainsController extends Controller
{
    public function index()
    {
        return view('admin.complains.index');
    }


    public function complainsList() {
        $complains = CustomerComplains::orderBy('id','DESC')->get();

         foreach($complains as $complain){
            $user_id = $complain->user_id;
            $user = User::where('id',$user_id)->first();
            if($user){
                $complain['name']=$user->name;
                $complain['email']=$user->email;
                $complain['phone']=$user->phone;
            }
        }
        return Datatables::of($complains)->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function complainDetails($id){
        $complain = CustomerComplains::find($id);
        $user_id = $complain->user_id;
        $user = User::where('id',$user_id)->first();
        return view('admin.complains.complain_details',compact('complain','user'));
    }

    public function complainAnswer($id,ComplainsRequest $request){
        $complain = CustomerComplains::find($id);
        if(!$request->has('resolved')){
            $request->resolved = 0;
        }
        $complain->update(['admin_answer'=>$request->admin_answer,'resolved'=>$request->resolved]);
        return redirect('admin/users-complains')->with('success','Updated Successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}

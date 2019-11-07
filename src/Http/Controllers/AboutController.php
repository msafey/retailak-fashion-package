<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;
use validate;
class AboutController extends Controller
{
    // index function
    public function index()
    {
        $content = null;
        $about = About::where('id','1')->first();
        if(isset($about))
        {
            $content = $about->content;
        }
        return view('admin.about.index' , compact('content'));
    }

    // store
    public function store( Request $request )
    {
        $this->validate($request, [
            'content'=> 'required|string'
        ]);

        // save new about content in table
        $about = About::where('id','1')->first();
        if(isset($about)) {
            // if there is about record update it
            $about->content = $request->content;
            $about->save();
            $request->session()->flash('success', 'About Us Updated successfully ');
            return redirect()->back();
        }
        else {
            return redirect()->back()->withErrors('problem in updating about us try again later');

        }
    }

    // about Api
    public function about_api()
    {
        $about = About::where('id','1')->first();
        return response()->json($about , 200);
    }
}

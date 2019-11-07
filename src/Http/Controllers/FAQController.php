<?php

namespace App\Http\Controllers;

use App\Models\faq;
use App\Models\faq_details;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use Carbon\Carbon;
class FAQController extends Controller
{
    //
    public function index()
    {
        return view('admin.faq.index');
    }

    public function get_faq()
    {
        $faq= faq::all();
        $x = 0;
        $faqs = array();
        foreach ($faq as $ask) {
            // check date first if set
            $created_at = new Carbon($ask->created_at);
            $date = new \DateTime($created_at);
            $created_at = $date->format('m/d/Y');

            $faqs[$x]['id'] = $ask->id;
            $faqs[$x]['created_at'] =$created_at;
            $faqs[$x]['title'] = $ask->title;
            $faqs[$x]['count'] = count($ask->details);
            $x++;
        }
        $data = collect($faqs);
        return Datatables::of($data)->make(true);
    }

    public function create()
    {
        return view('admin.faq.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required|string' ,
            'question.*' => 'required|string',
            'Answer.*' => 'required|string'
        ]);

       $faq = new faq();
       $faq->title = $request->title;
       $check = $faq->save();
       if($check)
       {
            foreach ($request->question as  $index => $q)
           {
                $faq_detail = new faq_details();
                $faq_detail->question = $q;
                $faq_detail->answer = $request->Answer[$index];
                $faq_detail->faq_id = $faq->id;
                $faq_detail->save();
           }
           $request->session()->flash('success', 'FAQ Created successfully ');
            return redirect('/admin/faq');
       }
       else
       {
           return redirect()->back()->withErrors('failed to save FAQ , try again later');
       }
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $obj = faq::find($id);
        if($obj)
        {
            $obj->delete();
            return 'true';
        }
        else
        {
            return 'false';
        }
    }

    public function delete_details(Request $request)
    {
        $id = $request->id;
        $obj = faq_details::find($id);
        if($obj)
        {
            $obj->delete();
            return 'true';
        }
        else
        {
            return 'false';
        }
    }

    public function edit($id)
    {
        // find faq
        $faq = faq::where('id' , $id)->first();
        if(isset($faq))
        {
            $faq->details;
            return view('admin.faq.edit', compact('faq'));
        }
        return redirect()->back()->withErrors('the FAQ you are looking not found');
    }

    // update
    public function update(Request $request)
    {
        $this->validate($request,[
            'title' => 'required|string' ,
            'question.*' => 'required|string',
            'Answer.*' => 'required|string',
        ]);

        $faq = faq::find($request->faq_id);
        if(isset($faq))
        {
            $faq->title = $request->title;
            $check = $faq->save();
            if($check)
            {
                foreach ($request->question as  $index => $q)
                {
                    $faq_detail = faq_details::find($request->detail_key[$index]);
                    if(!isset($faq_detail)) {
                        $faq_detail = new faq_details();
                    }
                    $faq_detail->question = $q;
                    $faq_detail->answer = $request->Answer[$index];
                    $faq_detail->faq_id = $faq->id;
                    $faq_detail->save();
                }
                $request->session()->flash('success', 'FAQ Updated successfully ');
                return redirect()->back();
            }
            else
            {
                return redirect()->back()->withErrors('failed to save FAQ , try again later');
            }
        }
        else
        {
            return redirect()->back()->withErrors('failed to save FAQ , try again later');
        }
    }

    // api
    public function listAllFAQ()
    {
        $faq = faq::all();
        if(isset($faq))
        {
            foreach ($faq as $x)
            {
                $x->details;
            }
        }
        return response()->json($faq,200);
    }
}

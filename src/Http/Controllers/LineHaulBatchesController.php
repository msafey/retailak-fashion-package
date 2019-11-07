<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use App\Http\Requests\LineHaulRequest;
use App\Models\LineHaulBatch;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Models\Image;
use Auth;
use File;

class LineHaulBatchesController extends Controller
{

    protected $imageController;

    public function __construct()
    {
        $this->imageController = new ImageController();

    }


    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('line_hul_batch'))
        {
            return view('admin.un-authorized');
        }

        return view('admin.line_haul_batch.index');
    }


    public function lineHaulBatchList()
    {
        if (!Auth::guard('admin_user')->user()->can('line_hul_batch'))
        {
            return view('admin.un-authorized');
        }

        $line_haul_batches = LineHaulBatch::all();
        return Datatables::of($line_haul_batches)->make(true);
    }


    public function create()
    {
        if (!Auth::guard('admin_user')->user()->can('line_hul_batch'))
        {
            return view('admin.un-authorized');
        }

        return view('admin.line_haul_batch.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(LineHaulRequest $request)
    {
        if (!Auth::guard('admin_user')->user()->can('line_hul_batch'))
        {
            return view('admin.un-authorized');
        }

        $line_haul_batch = LineHaulBatch::create($request->except(['_token', 'images']));
        if ($request->hasFile('images')) {
            $images = [];
            $line_images = $request->images;
            foreach ($line_images as $line_image) {
                $images[] = $this->imageController->saveImage($line_image, "line_haul_batch");
            }
            foreach ($images as $image) {
                $line_haul_batch->linesImage($image);
            }
        }
        $user = Auth::guard('admin_user')->user();

        $line_haul_batch->adjustments()->attach($user->id, ['key' => "LineHaulBatch", 'action' => "Added", 'content_name' => $line_haul_batch->id . ' driver_name' . $line_haul_batch->driver_name]);

        return redirect('admin/line-haul-batch')->with('success', 'Batch Created Successfully');
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

    public function edit($id)
    {
        if (!Auth::guard('admin_user')->user()->can('line_hul_batch'))
        {
            return view('admin.un-authorized');
        }

        $line_haul_batch = LineHaulBatch::find($id);
        $lines_images = $line_haul_batch->images()->get();
        // dd($lines_images);
        return view('admin.line_haul_batch.edit', compact('line_haul_batch', 'lines_images'));
    }


    public function update(LineHaulRequest $request, $id)
    {
        if (!Auth::guard('admin_user')->user()->can('line_hul_batch'))
        {
            return view('admin.un-authorized');
        }
        $requestData = $request->except(['_method', '_token', 'images']);
        LineHaulBatch::where('id', $id)->update($requestData);
        $line_haul = LineHaulBatch::find($id);
        if ($request->hasFile('images')) {
            if ($line_haul->images()) {
                $line_haul_images = $line_haul->images()->get();
            }
            if ($line_haul_images) {
                foreach ($line_haul_images as $line_image) {
                    $imgpath = public_path('imgs/line_haul_batch/' . $line_image->image);
                    File::delete($imgpath);
                    $imgthubmailpath = public_path('imgs/line_haul_batch/thumb/' . $line_image->image);
                    File::delete($imgthubmailpath);
                    $line_image->delete();
                }
            }
            $images = [];
            $line_images = $request->images;
            foreach ($line_images as $line_image) {
                $images[] = $this->imageController->saveImage($line_image, "line_haul_batch");
            }
            foreach ($images as $image) {
                $line_haul->linesImage($image);
            }
        }
        $user = Auth::guard('admin_user')->user();

        $line_haul->adjustments()->attach($user->id, ['key' => "LineHaulBatch", 'action' => "Edited", 'content_name' => $line_haul->id . ' driver_name' . $line_haul->driver_name]);
        return redirect('admin/line-haul-batch')->with('success', 'Batch Updated Successfully');
    }


    public function delete($id)
    {
        if (!Auth::guard('admin_user')->user()->can('line_hul_batch'))
        {
            return view('admin.un-authorized');
        }
        $line_haul_batch = LineHaulBatch::find($id);
        // dd($line_haul_batch);
        $images = $line_haul_batch->images();
        if ($images) {
            foreach ($images as $image) {
                $imgpath = public_path('imgs/line_haul_batch/' . $image->image);
                File::delete($imgpath);
                $imgthubmailpath = public_path('imgs/line_haul_batch/thumb/' . $image->image);
                File::delete($imgthubmailpath);
                $image->delete();
            }
        }
        if ($line_haul_batch) {
            $user = Auth::guard('admin_user')->user();

            $line_haul_batch->adjustments()->attach($user->id, ['key' => "LineHaulBatch", 'action' => "Deleted", 'content_name' => $line_haul_batch->name]);

            $line_haul_batch->delete();
        }

        return 'true';
    }


    public function deleteImage($id)
    {
        if (!Auth::guard('admin_user')->user()->can('line_hul_batch'))
        {
            return view('admin.un-authorized');
        }

        return DB::transaction(function () use ($id) {
            $id = intval($id);
            $lines_image = Image::findOrFail($id);
            File::delete(url('public/images/line_haul_batch/thumb/' . $lines_image->image));
            File::delete(url('public/images/line_haul_batch/' . $lines_image->image));
            $lines_image->delete();
            return redirect()->back()->with('success', ' Line Haul Image Deleted Successfully');
        });
    }


}

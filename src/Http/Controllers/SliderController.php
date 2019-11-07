<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('slider'))
        {
            return view('admin.un-authorized');
        }

        $slides = Slider::get();
        return view('admin/slides/list', compact('slides'));
    }

    public function addSlide()
    {
        if (!Auth::guard('admin_user')->user()->can('slider'))
        {
            return view('admin.un-authorized');
        }
        return view('admin/slides/add');
    }

    public function storeSlide(Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('slider'))
        {
            return view('admin.un-authorized');
        }
        $messages = [
            'main_image.required' => 'Main image is required',
            'type.required' => 'Slider type is required',
            'upper_text.required' => 'Upper text is required for this type of sliders',
            'main_text.required' => 'Main text is required for this type of sliders',
            'lower_text.required' => 'Lower text is required for this type of sliders',
            'small_image_1.required' => 'Small image 1 is required for this type of sliders',
            'small_image_2.required' => 'Small image 2 is required for this type of sliders',
        ];
        $this->validate($request, [
            'main_image' => 'required|image',
            'type' => 'required',
        ], $messages);
        if ($request->type == 'type2') {
            $this->validate($request, [
                'upper_text' => 'required',
                'main_text' => 'required',
                'lower_text' => 'required',
            ], $messages);
        } elseif ($request->type == 'type3') {
            $this->validate($request, [
                'upper_text' => 'required',
                'main_text' => 'required',
                'lower_text' => 'required',
                'small_image_1' => 'required|image',
                'small_image_2' => 'required|image',
            ], $messages);

        }

        if ($request->type == 'type3') {
            $small_image_1 = $request->file('small_image_1');
            $input['small_image_1'] = 'small1' . time() . '.' . $small_image_1->getClientOriginalExtension();
            $small_image_1Path = public_path('/imgs/slider');
            $small_image_1->move($small_image_1Path, $input['small_image_1']);

            $small_image_2 = $request->file('small_image_2');
            $input['small_image_2'] = 'small2' . time() . '.' . $small_image_2->getClientOriginalExtension();
            $small_image_2Path = public_path('/imgs/slider');
            $small_image_2->move($small_image_2Path, $input['small_image_2']);
        }

        $image = $request->file('main_image');
        $input['main_image'] = time() . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path('/imgs/slider');
        $image->move($destinationPath, $input['main_image']);

        // $this->postImage->add($input);
        $slide = new Slider;
        $slide->type = $request->type;
        $slide->img_1 = url('public/imgs/slider') . '/' . $input['main_image'];
        if (!is_null($request->img_1_url_checkbox)) {
            $slide->img_1_url = $request->img_1_url;
        }

        if (isset($input['small_image_1'])) {
            $slide->img_2 = url('public/imgs/slider') . '/' . $input['small_image_1'];
        }

        if (!is_null($request->img_2_url_checkbox)) {
            $slide->img_2_url = $request->img_2_url;
        }

        if (isset($input['small_image_2'])) {
            $slide->img_3 = url('public/imgs/slider') . '/' . $input['small_image_2'];
        }

        if (!is_null($request->img_3_url_checkbox)) {
            $slide->img_3_url = $request->img_3_url;
        }

        $slide->text_1 = $request->upper_text;
        // if(!is_null($request->text_1_url_checkbox))
        $slide->text_1_url = $request->text_1_url;
        $slide->text_2 = $request->main_text;
        // if(!is_null($request->text_2_url_checkbox))
        $slide->text_2_url = $request->text_2_url;
        $slide->text_3 = $request->lower_text;
        // if(!is_null($request->text_3_url_checkbox))
        $slide->text_3_url = $request->text_3_url;
        $slide->save();
        return redirect(url('admin/slides'));

    }

    public function editSlide($slideId)
    {
        if (!Auth::guard('admin_user')->user()->can('slider'))
        {
            return view('admin.un-authorized');
        }

        $slide = Slider::find($slideId);
        if (!$slide) {
            return redirect()->back()->withError('This Slide Does not exist');
        } else {
            //ee

            return view('admin/slides/edit', compact('slide'));
        }
    }

    public function updateSlide(Request $request, $slideId)
    {
        if (!Auth::guard('admin_user')->user()->can('slider'))
        {
            return view('admin.un-authorized');
        }

        $messages = [

            'type.required' => 'Slider type is required',
            'upper_text.required' => 'Upper text is required for this type of sliders',
            'main_text.required' => 'Main text is required for this type of sliders',
            'lower_text.required' => 'Lower text is required for this type of sliders',

        ];
        $this->validate($request, [
            'type' => 'required',
        ], $messages);
        if ($request->type == 'type2' || $request->type == 'type3') {
            $this->validate($request, [
                'upper_text' => 'required',
                'main_text' => 'required',
                'lower_text' => 'required',
            ], $messages);
        }

        if ($request->type == 'type3') {
            if (!is_null($request->file('small_image_1'))) {
                $small_image_1 = $request->file('small_image_1');
                $input['small_image_1'] = 'small1' . time() . '.' . $small_image_1->getClientOriginalExtension();
                $small_image_1Path = public_path('/imgs/slider');
                $small_image_1->move($small_image_1Path, $input['small_image_1']);
            }

            if (!is_null($request->file('small_image_2'))) {
                $small_image_2 = $request->file('small_image_2');
                $input['small_image_2'] = 'small2' . time() . '.' . $small_image_2->getClientOriginalExtension();
                $small_image_2Path = public_path('/imgs/slider');
                $small_image_2->move($small_image_2Path, $input['small_image_2']);
            }

        }
        if (!is_null($request->file('main_image'))) {
            $image = $request->file('main_image');
            $input['main_image'] = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/imgs/slider');
            $image->move($destinationPath, $input['main_image']);
        }

        // $this->postImage->add($input);
        $slide = Slider::find($slideId);
        // $slide->type = $request->type;
        if (isset($input['main_image'])) {
            $slide->img_1 = url('public/imgs/slider') . '/' . $input['main_image'];
        }

        if (!is_null($request->img_1_url_checkbox)) {
            $slide->img_1_url = $request->img_1_url;
        } else {
            $slide->img_1_url = '';
        }

        if (isset($input['small_image_1'])) {
            $slide->img_2 = url('public/imgs/slider') . '/' . $input['small_image_1'];
        }

        if (!is_null($request->img_2_url_checkbox)) {
            $slide->img_2_url = $request->img_2_url;
        } else {
            $slide->img_2_url = '';
        }

        if (isset($input['small_image_2'])) {
            $slide->img_3 = url('public/imgs/slider') . '/' . $input['small_image_2'];
        }

        if (!is_null($request->img_3_url_checkbox)) {
            $slide->img_3_url = $request->img_3_url;
        } else {
            $slide->img_3_url = '';
        }

        $slide->text_1 = $request->upper_text;
        if (!is_null($request->text_1_url_checkbox)) {
            $slide->text_1_url = $request->text_1_url;
        } else {
            $slide->text_1_url = '';
        }

        $slide->text_2 = $request->main_text;
        if (!is_null($request->text_2_url_checkbox)) {
            $slide->text_2_url = $request->text_2_url;
        } else {
            $slide->text_2_url = '';
        }

        $slide->text_3 = $request->lower_text;
        if (!is_null($request->text_3_url_checkbox)) {
            $slide->text_3_url = $request->text_3_url;
        } else {
            $slide->text_3_url = '';
        }

        $slide->save();
        return redirect(url('admin/slides'));

    }

    public function getSlider()
    {

        // if (!Auth::guard('admin_user')->user()->can('slider'))
        // {
        //     return view('admin.un-authorized');
        // }

        if (getFromCache('Slider')) {
            return getFromCache('Slider');
            // dd('Cached', getFromCache('Slider'));
        }
        $slider = array();
        $slides = Slider::get();

        foreach ($slides as $slideRow) {
            $slide = array();
            $slide['type'] = $slideRow->type;
            $slide['mainImg'] = $slideRow->img_1;
            $slide['mainImgUrl'] = $slideRow->img_1_url;
            $slide['upperText'] = $slideRow->text_1;
            $slide['upperTextUrl'] = $slideRow->text_1_url;
            $slide['mainText'] = $slideRow->text_2;
            $slide['mainTextUrl'] = $slideRow->text_2_url;
            $slide['lowerText'] = $slideRow->text_3;
            $slide['lowerTextUrl'] = $slideRow->text_3_url;
            $slide['smallImg1'] = $slideRow->img_2;
            $slide['smallImg1Url'] = $slideRow->img_2_url;
            $slide['smallImg2'] = $slideRow->img_3;
            $slide['smallImg2Url'] = $slideRow->img_3_url;
            $slider[] = $slide;
        }
        return putInCache('Slider', $slider);
    }

    public function deleteSlide($slideId)
    {
        if (!Auth::guard('admin_user')->user()->can('slider'))
        {
            return view('admin.un-authorized');
        }

        $slide = Slider::find($slideId);
        if (!$slide) {
            return redirect()->back();
        } else {
            $slideId = $slide->id;
            $slide->delete();
            return $slideId;
        }

    }

}

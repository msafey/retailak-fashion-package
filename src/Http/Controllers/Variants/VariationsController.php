<?php

namespace App\Http\Controllers\Variants;

use App\Http\Controllers\Controller;
use App\Models\VariantsMeta;
use App\Models\Variations;
use Auth;
use DB;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class VariationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('variations'))
        {
            return view('admin.un-authorized');
        }

        return view('admin.variants.index');
    }

    public function variantsList()
    {
        if (!Auth::guard('admin_user')->user()->can('variations'))
        {
            return view('admin.un-authorized');
        }

        $variants = Variations::orderBy('id', 'ASC')->get();
        return Datatables::of($variants)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::guard('admin_user')->user()->can('variations'))
        {
            return view('admin.un-authorized');
        }

        return view('admin.variants.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('variations'))
        {
            return view('admin.un-authorized');
        }

        return DB::transaction(function () use ($request) {
            $variation = new Variations;
            $is_color = !$request->has('is_color') ? 0 : 1;
            $is_size = !$request->has('is_size') ? 0 : 1;
            $affecting_stocks = !$request->has('affecting_stock') ? 0 : 1;

            $has_special_images = !$request->has('has_special_images') ? 0 : 1;
            $variation = Variations::create(['name' => $request->name, 'name_en' => $request->name_en, 'affecting_stock' => $affecting_stocks, 'key' => $request->key, 'has_special_images' => $has_special_images, 'status' => 1, 'is_color' => $is_color, 'is_size' => $is_size]);
            $variation_value_en = array_filter($request->input('variation_value_en'));
            $variation_value = $request->input('variation_value');
            $variation_id = $variation->id;
            $color_codes = $is_color == 1 ? $request->input('color_codes') : [];
            $variation_codes = $request->input('variation_codes');

            if (isset($variation_value_en)) {
                foreach ($variation_value_en as $key => $variant) {
                    $num = null;
                    if ($request->has('key')) {
                        if ($request->is_size == 1 || $request->is_color == 1) {
                            $num = $this->getLastItemCode($num);
                        }
                    }
                    if (!isset($variation_codes[$key])) {
                        $variation_codes[$key] = null;
                    }
                    if (!isset($color_codes[$key])) {
                        $color_codes[$key] = null;

                    }
                    $variation->variation($variation_value_en[$key], $variation_value[$key], $variation_id, $color_codes[$key], $num, $variation_codes[$key]);
                }
            }
            $user = Auth::guard('admin_user')->user();
            $variation->adjustments()->attach($user->id, ['key' => "Variation", 'action' => "Added", 'content_name' => $variation->name]);

            return redirect('admin/variations')->with('success', 'Variation Created Successfully');
        });
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

    public function Status($id)
    {
        if (!Auth::guard('admin_user')->user()->can('variations'))
        {
            return view('admin.un-authorized');
        }

        $status = Variations::where('id', $id)->select('status')->first();
        if ($status->status == 0) {
            Variations::where('id', $id)->update(['status' => '1']);
            return redirect('admin/variations')->with('Variation Activated Successfully');

        } else {
            Variations::where('id', $id)->update(['status' => '0']);

            return redirect('admin/variations')->with('success', 'Variations  De-Activated Successfully');

        }
    }

    public function edit($id)
    {
        if (!Auth::guard('admin_user')->user()->can('variations'))
        {
            return view('admin.un-authorized');
        }

        $variation = Variations::find($id);
        $variation_meta = $variation->variantsMeta;
        return view('admin.variants.edit', compact('variation', 'variation_meta'));
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
        if (!Auth::guard('admin_user')->user()->can('variations'))
        {
            return view('admin.un-authorized');
        }
        return DB::transaction(function () use ($request, $id) {
            $variation = Variations::find($id);
            $affecting_stocks = !$request->has('affecting_stock') ? 0 : 1;
            $has_special_images = !$request->has('has_special_images') ? 0 : 1;
            $is_color = !$request->has('is_color') ? 0 : 1;
            $is_size = !$request->has('is_size') ? 0 : 1;
            // dd($is_size);
            $variation->update(['name' => $request->name, 'name_en' => $request->name_en, 'affecting_stock' => $affecting_stocks, 'key' => $request->key, 'has_special_images' => $has_special_images, 'status' => 1, 'is_color' => $is_color, 'is_size' => $is_size]);
            $variation_value_en = array_filter($request->input('variation_value_en'));
            $variation_value = $request->input('variation_value');
            $variation_id = $variation->id;
            $variation_codes = $request->input('variant_code');

            $color_codes = $is_color == 1 ? $request->input('color_codes') : [];
            $variant_meta_ids = $request->input('variant_metas');
            if (isset($variation_value_en)) {
                foreach ($variation_value_en as $key => $variant) {
                    if ($variant_meta_ids[$key] == 0) {

                        $num = null;
                        if ($request->has('key')) {
                            if ($request->key == 'sizes' || $request->key == 'colors') {
                                $num = $this->getLastItemCode($num);
                            }
                        }
                        if (!isset($color_codes[$key])) {
                            $color_codes[$key] = '';
                        }
                        if (!isset($variation_codes[$key])) {
                            $variation_codes[$key] = '';
                        }
                        $variation->variation($variation_value_en[$key], $variation_value[$key], $variation_id, $color_codes[$key], $num, $variation_codes[$key]);
                    } else {

                        if (count($color_codes) == 0) {
                            $color_code = null;
                        } else {
                            $color_code = $color_codes[$key];
                        }

                        if (count($variation_codes) == 0) {
                            $variation_code = null;
                        } else {
                            $variation_code = $variation_codes[$key];

                        }

                        // dd(var_dump($color_codes[$key]));
                        $variant_meta = VariantsMeta::where('id', intval($variant_meta_ids[$key]))->update(['code' => $color_code, 'variation_value_en' => $variation_value_en[$key], 'variation_value' => $variation_value[$key], 'variant_data_id' => $variation_id, 'variant_code' => $variation_code]);
                    }
                }
            }
            $user = Auth::guard('admin_user')->user();
            $variation->adjustments()->attach($user->id, ['key' => "Variation", 'action' => "Edited", 'content_name' => $variation->name_en]);

            return redirect('admin/variations')->with('success', 'Variation Updated Successfully');
        });
    }

    public function removeVariantValue($id)
    {
        $variation_meta = VariantsMeta::where('id', $id)->first();
        if ($variation_meta) {
            $variation_meta->delete();
            return 'success';
        }
    }

    public function getLastItemCode($num)
    {
        $latest_item_code = VariantsMeta::whereNotNull('item_code')->orderBy('id', 'desc')->select('item_code')->first();
        $num = 0;
        if ($latest_item_code) {
            $num = (int)$latest_item_code->item_code;
        }
        // $num = $this->updateVariantsItemCode($num);
        $num += 1;
        $num = sprintf("%02d", $num);
        return $num;

    }

    public function delete($id)
    {
        if (!Auth::guard('admin_user')->user()->can('variations'))
        {
            return view('admin.un-authorized');
        }

        $variation = Variations::findOrFail($id);

        if ($variation) {
            $variation_meta = $variation->variantsMeta;
            foreach ($variation_meta as $meta) {
                $meta->delete();
            }
            $user = Auth::guard('admin_user')->user();
            $variation->adjustments()->attach($user->id, ['key' => "Variations", 'action' => "Deleted", 'content_name' => $variation->name_en]);
            $variation->delete();
            return 'true';
        } else {
            return 'false';
        }

        // return redirect($brand->path())->with('success','Brand Deleted Successfully');

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

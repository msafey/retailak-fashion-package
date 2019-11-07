<?php

namespace App\Http\Controllers\PriceLists;

use App\Http\Controllers\Controller;
use App\Models\PriceList;
use DB;
use File;
use Image;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;

class PriceListsController extends Controller
{

    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('price_list'))
        {
            return view('admin.un-authorized');
        }

        return view('admin.price_lists.index');
    }


    public function priceList()
    {
        if (!Auth::guard('admin_user')->user()->can('price_list'))
        {
            return view('admin.un-authorized');
        }

        $price_list = PriceList::all();
        return Datatables::of($price_list)->make(true);
    }

    public function create()
    {
        return view('admin.price_lists.add');
    }

    public function store(Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('price_list'))
        {
            return view('admin.un-authorized');
        }

        $currency_code = request('currency_code');
        $price_list_name = request('price_list_name');
        $type = json_encode(request('type'));

        PriceList::create(
            ['currency_code' => $currency_code, 'price_list_name' => $price_list_name, 'type' => $type]
        );

        return redirect('admin/price-list')->withSucess('PriceList Created Successfully');

    }

    public function edit($id)
    {
        if (!Auth::guard('admin_user')->user()->can('price_list'))
        {
            return view('admin.un-authorized');
        }

        $price_list = PriceList::whereId($id)->first();
        if ($price_list) {
            $price_list_type = isset($price_list->type) ? json_decode($price_list->type) : [];
        }
        return view('admin.price_lists.edit', compact('price_list', 'price_list_type'));

    }

    public function update($id)
    {
        if (!Auth::guard('admin_user')->user()->can('price_list'))
        {
            return view('admin.un-authorized');
        }

        $price_list = PriceList::whereId($id)->first();
        $currency_code = request('currency_code');
        $price_list_name = request('price_list_name');
        $type = json_encode(request('type'));

        if ($price_list) {
            $price_list->currency_code = $currency_code;
            $price_list->price_list_name = $price_list_name;
            $price_list->type = $type;
            $price_list->save();
        }

        return redirect('admin/price-list')->with('success', 'Updated Successfully');
    }

    public function delete($id)
    {
        if (!Auth::guard('admin_user')->user()->can('price_list'))
        {
            return view('admin.un-authorized');
        }

        $price_list = PriceList::findOrFail($id);

        if ($price_list) {
            $price_list->delete();
        }

        return 'true';
        return redirect($price_list->path())->with('success', 'Price List Deleted Successfully');


    }

}

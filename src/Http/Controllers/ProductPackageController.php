<?php

namespace App\Http\Controllers;

use App\Models\ProductPackage;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class ProductPackageController extends Controller {
	//
	public function index() {
		return view('admin/product-packages/index');
	}

	public function packagesList() {
		$data = ProductPackage::get();
		return DataTables::of($data)->make(true);
	}

	public function create() {
		return view('admin/product-packages/create');
	}

	public function store(Request $request) {
		$package = new ProductPackage;
		$package->name_en = $request->name_en;
		$package->name_ar = $request->name_ar;
		$package->code = $request->code;
		$package->save();
		return redirect(url('admin/productPackages'))->withSuccess(['Package Was Added Successfully']);
	}

	public function delete($id) {
		$package = ProductPackage::find($id);
		if ($package) {
			$package->delete();
		}

		return 'success';

	}
}

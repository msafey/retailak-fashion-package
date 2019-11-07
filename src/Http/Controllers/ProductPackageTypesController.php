<?php

namespace App\Http\Controllers;

use App\Models\ProductPackageType;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class ProductPackageTypesController extends Controller {
	//
	public function index() {
		return view('admin/product-package-types/index');
	}

	public function packageTypesList() {
		$data = ProductPackageType::select('id', 'name_en', 'uom')->get();
		return DataTables::of($data)->make(true);
	}

	public function create() {
		return view('admin/product-package-types/create');
	}

	public function store(Request $request) {
		$packageType = new ProductPackageType;
		$packageType->name_en = $request->name_en;
		$packageType->name_ar = $request->name_ar;
		$packageType->code = $request->code;
		$packageType->uom = $request->uom;
		$packageType->save();
		return redirect(url('admin/productPackagetypes'))->withSuccess(['Package Type Was Added Successfully']);
	}

	public function edit($id) {
		$packageType = ProductPackageType::find($id);

		return view('admin/product-package-types/edit', compact('packageType'));
	}
	public function update(Request $request, $id) {
		$packageType = ProductPackageType::find($id);
		if ($packageType) {
			$packageType->name_en = $request->name_en;
			$packageType->name_ar = $request->name_ar;
			$packageType->code = $request->code;
			$packageType->uom = $request->uom;
			$packageType->save();
		}

		return redirect(url('admin/productPackagetypes'))->withSuccess(['Package Type Was updated Successfully']);
	}

	public function delete($id) {
		$packageType = ProductPackageType::find($id);
		if ($packageType) {
			$packageType->delete();
		}

		return 'success';

	}
}

<?php

namespace App\Http\Controllers\BundlesCms;

use App\Models\Bundle;
use App\Models\undleItem;
use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;
use Yajra\Datatables\Datatables;

class BundlesController extends Controller {

	// edit view Bundels
	public function edit($id, Request $request) {
		// select all products that is not IsBundel = 0 , and not items of these
		$ids_execluded = array();
		try {

			$bundle = Bundle::where('bundel_id', $id)->first();
			if ($bundle) {
				$bundel_items_check = false;
				$budel_items = $bundle->items;
				if (count($budel_items) > 0) {
					$bundel_items_check = true;
					foreach ($budel_items as $item) {
						$ids_execluded[] = $item->item_id;
					}
				} else {
					$ids_execluded[] = -1;
				}
				$producst_avilabel = Products::where('is_bundle', 0)->whereNotIn('id', $ids_execluded)->get();
				return view('/admin/bundles/edit', compact('bundle', 'bundel_items_check', 'producst_avilabel'));
			} else {
				return redirect()->back()->withErrors(['bundle does not exist']);

			}

		} catch (Exception $e) {
			redirect()->back();
		}
	}
	// Bundel Item Data Tabel
	public function BundelItems_DataTable(Request $request) {
		$bundle = Bundle::where('bundel_id', $request->Bundel_id)->first();
		$all_bundel_items = array();
		$bundel_items = $bundle->items;
		$x = 0;
		foreach ($bundel_items as $item) {
			$all_bundel_items[$x]['item_id'] = $item->item_id;
			$all_bundel_items[$x]['id'] = $item->id;
			$all_bundel_items[$x]['product_name'] = $item->product->name_en;
			$all_bundel_items[$x]['quantity'] = $item->qty;
			$all_bundel_items[$x]['action'] = '';
			$x++;
		}
		$data = collect($all_bundel_items);
		return Datatables::of($data)->make(true);
	}

	public function deleteBundelItem(Request $request) {
		$bundle = BundleItem::where('item_id', '=', $request->id)->get();
		if (count($bundle) > 0) {
			$bundle[0]->delete();
			$request->session()->flash('success', 'Bundel item deleted successfully');
			return redirect()->back();
		} else {
			return redirect()->back();
		}
	}

	public function editBundelItem(Request $request) {
		$bundle = BundleItem::find($request->id);
		if ($bundle) {
			$bundle->qty = $request->quantity_budel;
			$bundle->save();
			$request->session()->flash('success', 'Bundel item quantity updated successfully');
			return redirect()->back();
		} else {
			return redirect()->back();
		}

	}

	public function Additems(Request $request) {

		$this->validate($request, [
			'item_id' => 'required|string',
			'bundel_id' => 'required|string',
			'quantity' => 'required|integer',
		]);

		$item = new BundleItem;
		$item->item_id = $request->item_id;
		$item->parent_bundle_id = $request->bundel_id;
		$item->qty = $request->quantity;

		$item->save();

		return $item->id;
	}

}

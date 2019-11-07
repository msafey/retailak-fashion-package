<?php

namespace App\Http\Controllers;

use App\Models\salaries_drawing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Yajra\Datatables\Datatables;

class Salaries_drawingController extends Controller {
	public function index() {
		return view('admin.Salaries Drawing.index');
	}

	public function addView() {
		return view('admin.Salaries Drawing.add');
	}

	public function CreateDrawing(Request $request) {
		// validation first
		$this->validate($request, [
			'money' => 'required|numeric',
			'person' => 'required|string',
			'reason' => 'required|string',
			'type' => 'required',
		]);

		// then save object
		$Drawing = new salaries_drawing();
		$Drawing->admin_id = Auth::guard('admin_user')->user()->id;
		$Drawing->employee = $request->person;
		$Drawing->cost = $request->money;
		$Drawing->type = $request->type;
		$Drawing->Reason = $request->reason;
		$Drawing->save();
		Session::flash('success', 'your Drawing generated successfully');
		return redirect(url('admin/Salaries'));

		// return redirect()->route('addDrawingView');

	}
	public function AllSalaries(Request $request) {
		$filter = $request->filter;
		$allDrawings = array();
		$x = 0;
		if ($filter == 'no' || !$filter) {
			$AllDaTa = salaries_drawing::all();
		} else {
			$AllDaTa = salaries_drawing::whereDate('created_at', '=', $filter)->get();
		}
		foreach ($AllDaTa as $darwing) {
			// check date first if set

			$type = null;
			$admin_name = $darwing->admin->name;
			if ($darwing->type == 0) {
				$type = 'Salary';
			} else {
				$type = 'other drawings';
			}

			$created_at = new Carbon($darwing->created_at);
			$date = new \DateTime($created_at);
			$created_at = $date->format('m/d/Y');

			$allDrawings[$x]['cost'] = $darwing->cost;
			$allDrawings[$x]['admin_name'] = $admin_name;
			$allDrawings[$x]['person'] = $darwing->employee;
			$allDrawings[$x]['type'] = $type;
			$allDrawings[$x]['id'] = $darwing->id;
			$allDrawings[$x]['date'] = $created_at;
			$x++;
		}
		$data = collect($allDrawings);
		return Datatables::of($data)->make(true);
	}

	public function delete(Request $request) {
		$id = $request->id;
		$obj = salaries_drawing::find($id);
		if ($obj) {
			$obj->delete();
			return 'true';
		} else {
			return 'false';
		}
	}

	public function EditView($id) {
		$drawing = salaries_drawing::find($id);
		if ($drawing) {
			return view('admin.Salaries Drawing.edit', compact('drawing'));
		} else {
			return redirect()->back();
		}
	}

	public function EditDrawingRequest(Request $request) {
		$Drawing = salaries_drawing::find($request->id);
		if ($Drawing) {
			// validation first
			$this->validate($request, [
				'money' => 'required|numeric',
				'person' => 'required|string',
				'reason' => 'required|string',
				'type' => 'required',
			]);

			// then save object
			$Drawing->employee = $request->person;
			$Drawing->cost = $request->money;
			$Drawing->type = $request->type;
			$Drawing->Reason = $request->reason;
			$Drawing->save();
			Session::flash('success', 'Data Edited successfully');
			return redirect()->back();

		} else {
			return redirect()->back();
		}
	}
}

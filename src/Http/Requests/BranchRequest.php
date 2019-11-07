<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
          'branch_name' => 'required',
          'warehouse_id' => 'required',
          'district_id' => 'required',
        ];
    }

    public function message()
    {
        return [
            'name' => 'Warehouse Is Required',
            'from' => 'Time From Is Required',
            'to' => 'Time to Is Required',
        ];
    }
}

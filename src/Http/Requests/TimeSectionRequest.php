<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimeSectionRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
          'name' => 'required',
          'name_en'=>'required',
          'from' => 'required',
          'to' => 'required'
        ];
    }

    public function message()
    {
        return [
            'name' => 'Name Is Required',
            'name_en'=>'English Name Is Required',
            'from' => 'Time From Is Required',
            'to' => 'Time to Is Required',
        ];
    }
}

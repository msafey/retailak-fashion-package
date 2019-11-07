<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddDeliveryManRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
          'name' => 'required',
          'mobile' => 'required|unique:delivery__men|max:255',
          'password' => 'required',
          'gender'=>'required',
          'date_of_birth'=>'required',
          'date_of_joining'=>'required',
          'delivery_car_id'=>'required'
        ];
    }

    public function message()
    {
        return [
            'name' => 'Name Is Required',
            'mobile' => 'Mobile Is Required And Unique',
            'password' => 'Password Is Required'
        ];
    }
}

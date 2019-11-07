<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditDeliveryManRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [




            'delivery_car_id'=>'required',
            'name' => 'required',
            'gender'=>'required',
            'date_of_birth'=>'required',
            'date_of_joining'=>'required'
//          'password' => 'required'
        ];
    }

    public function message()
    {
        return [
            'name' => 'Name Is Required',
//            'password' => 'Password Is Required'
        ];
    }
}

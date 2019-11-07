<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LineHaulRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'driver_name'=>'required',
            'car_plate_number'=>'required',
            'purchase_order_number'=>'required',
        ];
    }
}

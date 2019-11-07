<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            // 'email' => 'required|string|email|max:255|unique:users',

            'password' => 'required|string|min:6|confirmed',
            'street'=>'required',
            // 'apartment_no'=>'required',
            // 'floor_no'=>'required',
            // 'building_no'=>'required',
            // 'city'=>'required',
            'district_id'=>'required',
            // 'address_phone'=>'required|numeric',
            // 'nearest_landmark'=>'required',
            // 'title'=>'required',
        ];
    }
}

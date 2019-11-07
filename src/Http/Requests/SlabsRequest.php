<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SlabsRequest extends FormRequest
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
            'slab_name' =>'required' ,
            'min_amount_money'=> 'required|numeric',
            'discount_type' => 'required',
            'discount_rate' => 'required|numeric'
        ];
    }
}

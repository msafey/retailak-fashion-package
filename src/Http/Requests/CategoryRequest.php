<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                {
                    return [
                        'name' => 'required',
                        'name_en' => 'required',
                        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    ];
                }
            case 'PATCH':
                {
                    return [
                        'name' => 'required',
                        'name_en' => 'required',
                        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    ];
                }
            default:
                break;
        }
    }
}

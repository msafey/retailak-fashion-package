<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentsRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

//

    public function rules()
    {

        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                    'invoice_id' => 'required',
                    'payment_mode_id' => 'required',
                    'posting_date' => 'required',
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'payment_mode_id' => 'required',
                    'posting_date' => 'required',
                ];
            }
            default:
                break;
        }
    }

    public function message()
    {
        return [
            'posting_date' => 'Posting Date Is Required',
            'payment_mode_id' => 'Payment Mode Is Required',
        ];
    }
}

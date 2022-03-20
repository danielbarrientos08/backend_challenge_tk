<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportGenerateRequest extends FormRequest
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
           'title'=>'bail|required|max:100',
           'start_date'=>'bail|required|date',
           'end_date'=>'bail|required|after_or_equal:start_date',
        ];
    }
}

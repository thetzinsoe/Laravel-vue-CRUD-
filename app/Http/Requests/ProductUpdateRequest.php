<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'=>'string|nullable',
            'price'=>'numeric|nullable'
        ];
    }
    public function messages()
    {
        return[
            'name.string'=>'နာမည်သည်စာသားဖြစ်ရမည်။',
            'price.numeric'=>'စျေးနှုန်းသည် ဂဏန်းဖြစ်ရမည်။',
        ];
    }

}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'price' => 'nullable|numeric',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'နာမည်ထည့်ရန်လိုပါသည်။',
            'name.string' => 'နာမည်သည်စာသားဖြစ်ရမည်။',
            'price.required' => 'စျေးနှုန်းထည့်ရန်လိုပါသည်။',
            'price.numeric' => 'စျေးနှုန်းသည် ဂဏန်းဖြစ်ရမည်။',
        ];
    }
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator, $this->response(
            $this->formatErrors($validator)
        ));
    }


    // $validator = Validator::make($request->all(), [
    //     'name' => 'required|string',
    //     'price' => 'required|numeric',
    // ],[
    //         'name.required'=>"နာမည်ထည့်။",
    //         'price.required'=>"စျေးနှုန်းထည့်။"
    //     ]);

    // if ($validator->fails()) {
    //     return response()->json(['errors' => $validator->errors()], 400);
    // }
}

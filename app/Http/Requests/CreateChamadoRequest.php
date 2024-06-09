<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateChamadoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $data = [];
        $data['client_id'] = auth()->user()->id;
        $this->merge($data);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (auth()->user()->type != "C") {
                $validator->errors()->add('client_id', 'Você não pode criar chamados!');
            }
        });
    }


    public function rules()
    {
        $validate =  [
            'client_id' => ['required'],
            'title' => ['required', 'max:190'],
            'description' => ['required'],
        ];
        // if (!empty($this->anexed_files)) {
        //     $validate['anexed_files.*'] = ['mimes:jpeg,png,jpg,gif,svg,pdf,xls,xlsx,csv'];
        // }
        return $validate;
    }
}

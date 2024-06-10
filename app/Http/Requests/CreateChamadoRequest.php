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
                $validator->errors()->add('client_id', 'Você é um colaborador/administrador do sistema! Somente clientes podem gerar chamados.');
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
        return $validate;
    }
}

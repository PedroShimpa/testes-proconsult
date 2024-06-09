<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'cpf' =>  preg_replace("/[^0-9]/", "", $this->cpf),
        ]);
    }

    public function rules()
    {
        return [
            'name' => ['required', 'max:190'],
            'email' => ['required', 'max:190', 'unique:users', 'email:rfc,dns'],
            'cpf' => ['required', 'max:11', 'unique:users', 'min:11'],
            'password' => ['required', 'max:30']
        ];
    }

    public function messages()
    {
        return [
            'cpf.unique' => 'Este CPF ja esta sendo ultilizado',
            'email.unique' => 'Este E-mail ja esta sendo ultilizado',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->validaCPF()) {
                $validator->errors()->add('cpf', 'O cpf informado não é valido!');
            }
        });
    }

    private function validaCPF()
    {

        $cpf = preg_replace('/[^0-9]/is', '', $this->cpf);

        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }
}

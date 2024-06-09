<?php

namespace App\Http\Requests;

use App\Models\Chamado;
use Illuminate\Foundation\Http\FormRequest;

class CreateChamadoRespostaRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $data = [];
        $data['user_id'] = auth()->user()->id;
        $data['chamado_id'] = $this->id;
        $this->merge($data);
    }

    public function rules()
    {
        $validate =  [
            'chamado_id' => ['required'],
            'user_id' => ['required'],
            'content' => ['required'],
        ];
        return $validate;
    }


    public function withValidator($validator)
    {

        $validator->after(function ($validator) {
            /**
             * ira verificar se o usuario é um admin ou o dono do chamado, se não for, não pode interagir com o chamado
             */
            $chamado = Chamado::where('id', $this->id)->first();

            if ($chamado->status == 'F') {
                $validator->errors()->add('chamado_id', 'Este chamado ja esta finalizado e não pode ter novas respostas.');
            }
            if (auth()->user()->type != 'A' && $chamado->client_id != auth()->user()->id) {
                $validator->errors()->add('user_id', 'Você não pode interagir com este chamado');
            }
        });
    }
}

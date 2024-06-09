<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chamado extends Model
{
    protected $fillable = ['title', 'description', 'client_id', 'status'];

    protected $possibleStatus = ['A' => "Aguardando", "EA" => "Em Atendimento", "F" => "Finalizado"];

    public function getForClient($id = null)
    {
        $model = $this->where('client_id', auth()->user()->id);

        return  $this->commonSearch($model, $id);
    }

    public function updateByid($id, $data)
    {
        return $this->where('id', $id)->update($data);
    }

    public function getForAdmin($id = null)
    {
        return $this->commonSearch($this, $id);
    }

    public function commonSearch($model, $id = null)
    {
        if (!empty($id)) {
            $model = $model->where('id', $id);
        }
        $select = $model->select(
            'id',
            'title',
            'description',
            'status',
            'created_at'
        )->get();

        $select->filter(function ($query) {
            $query->status = $this->possibleStatus[$query->status] ?? "Erro";
        });
        return $select;
    }
}

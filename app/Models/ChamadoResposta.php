<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChamadoResposta extends Model
{

    protected $table = 'chamado_respostas';

    protected $fillable = [
        'chamado_id',
        'user_id',
        'content'
    ];

    public function getByChamadoId($chamadoId)
    {
        return $this->select('user.name as user', $this->table . '.content', $this->table . '.created_at')->join('users as user', 'user.id', '=', $this->table . '.user_id')->where('chamado_id', $chamadoId)->orderBy($this->table . '.id', 'asc')->get();
    }
}

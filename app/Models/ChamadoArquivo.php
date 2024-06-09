<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChamadoArquivo extends Model
{
    protected $fillable = [
        'chamado_id',
        'filename',
        'file'
    ];

    public function getByChamadoId($chamadoId)
    {
        return $this->select('filename', 'file')->where('chamado_id', $chamadoId)->get();
    }
}

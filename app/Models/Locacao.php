<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locacao extends Model
{
    use HasFactory;

    protected $table = 'locacoes';
    protected $fillable = [
        'cliente_id', 
        'carro_id',
        'data_inicio_periodo',
        'data_final_previsto_periodo',
        'data_final_realizado_periodo',
        'valor_diaria',
        'km_inicial',
        'km_final'
    ];

    public function rules()
    {
        return [];
    }

    public function clientes()
    {
        //Uma locação tem muitos clientes
        return $this->hasMany('App\Models\Cliente');
    }

    public function carros()
    {
        //Uma locação tem muitos carros
        return $this->hasMany('App\Models\Carro');
    }
}

<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class ModeloRepository
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectAtributosRegistrosRelacionados($atributos)
    {
        $this->model = $this->model->with($atributos);
    }

    public function filtro($filtros)
    {
        $filtros = explode(';', $filtros);

        foreach($filtros as $chave => $condicao){
            //Remove o : e transforma a requisição em um array
            $c = explode(':', $condicao);
            $this->model = $this->model->where($c[0], $c[1], $c[2]);
        }
    }

    public function selectAtributos($atributos)
    {
        $this->model = $this->model->selectRaw($atributos);
    }

    public function getResultado()
    {
        return $this->model->get();
    }
}

?>
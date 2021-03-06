<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $table = 'marcas';
    protected $fillable = ['nome', 'imagem'];

    public function rules()
    {
        return [
            'nome' => 'required|unique:marcas,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png,jpeg'
        ];
    }

    public function feedback()
    {
        return [
            'required'     => 'O campo :attribute é obrigatório',
            'nome.unique'  => 'O nome da marca já existe',
            'nome.min'     => 'O nome precisa ter no mínimo 3 caracteres',
            'imagem.mimes' => 'A imagem precisar ser do tipo PNG ou JPEG'
        ];
    }

    public function modelos()
    {
        //Uma marca tem vários modelos
        return $this->hasMany('App\Models\Modelo');
    }
}

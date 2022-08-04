<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $table = 'modelos';
    protected $fillable = [
        'marca_id',
        'nome',
        'imagem',
        'numero_portas',
        'lugares',
        'air_bag',
        'abs'
    ];

    public function rules()
    {
        return [
            'marca_id'      => 'exists:marcas,id',
            'nome'          => 'required|unique:modelos,nome,'.$this->id.'|min:3',
            'imagem'        => 'required|file|mimes:png,jpeg',
            'numero_portas' => 'required|integer|digits_between:1,5',
            'lugares'       => 'required|integer|digits_between:1,8',
            'air_bag'       => 'required',
            'abs'           => 'required'
        ];
    }
    /*
    public function feedback()
    {
        return [
            'required'     => 'O campo :attribute é obrigatório',
            'nome.unique'  => 'O nome da marca já existe',
            'nome.min'     => 'O nome precisa ter no mínimo 3 caracteres',
            'imagem.mimes' => 'A imagem precisar ser do tipo PNG ou JPEG'
        ];
    }
    */
    public function marca()
    {
        //Um modelo pertence a uma marca
        return $this->belongsTo('App\Models\Marca');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $fillable = ['nome', 'cpf'];

    public function rules()
    {
        return [
            'nome'  => 'required|min:3',
            'cpf'   => 'required|min:14|max:14'
        ];
    }
}

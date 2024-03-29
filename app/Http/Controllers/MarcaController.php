<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Repositories\MarcaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    public function __construct(Marca $marca){
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $marcaRepository = new MarcaRepository($this->marca);

        if($request->has('atributos_modelo')){
            $atributos_modelo = 'modelos:id,'.$request->atributos_modelo;

            $marcaRepository->selectAtributosRegistrosRelacionados($atributos_modelo);
        }else{
            $marcaRepository->selectAtributosRegistrosRelacionados('modelos');
        }

        if($request->has('filtro')){
            $marcaRepository->filtro($request->filtro);
        }
        if($request->has('atributos')){
            $marcaRepository->selectAtributos($request->atributos);
        }

        return response()->json($marcaRepository->getResultadoPaginado(10), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Pega a validação dos campos nome e imagem do Model
        
        $regras = $this->marca->rules();
        $feedback = $this->marca->feedback();

        $request->validate($regras, $feedback);

        $imagem = $request->file('imagem');
        $imagem_path = $imagem->store('imagens','public');

        $marca = $this->marca->create([
            'nome'   => $request->nome,
            'imagem' => $imagem_path
        ]);

        return response()->json($marca, 201);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id);

        if($marca === null){
            return response()->json(['erro' => 'O recurso solicitado não existe'], 404);
        }

        return response()->json($marca, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);

        if($marca === null){
            return response()->json(['erro' => 'Impossível atualizar. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH'){
            $regrasDinamicas = [];

            //Percorrendo todas as regras definidas no Model
            foreach ($marca->rules() as $input => $regra) {

                //Coletar apenas as regras aplicavéis aos parâmetros da requisição
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $marca->feedback());

        }else{
            $request->validate($marca->rules(), $marca->feedback());

        }

        //Preencher o objeto marca com os dados do request
        $marca->fill($request->all());

        //Remove uma imagem antiga e mantém a atual
        if($request->file('imagem')){
            Storage::disk('public')->delete($marca->imagem);

            $imagem = $request->file('imagem');
            $imagem_path = $imagem->store('imagens','public');
            $marca->imagem = $imagem_path;
        }

        $marca->save();

        return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);

        if($marca === null){
            return response()->json(['erro' => 'Impossível Excluír. O recurso solicitado não existe'], 404);
        }
        //Remove a imagem
        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();

        return response()->json(['msg' => 'Marca excluída com sucesso!'], 200);
    }
}

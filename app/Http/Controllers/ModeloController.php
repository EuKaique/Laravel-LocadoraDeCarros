<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Repositories\ModeloRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    public function __construct(Modelo $modelo){
        $this->modelo = $modelo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $modeloRepository = new ModeloRepository($this->modelo);

        if($request->has('atributos_marca')){
            $atributos_marca = 'marca:id,'.$request->atributos_marca;

            $modeloRepository->selectAtributosRegistrosRelacionados($atributos_marca);
        }else{
            $modeloRepository->selectAtributosRegistrosRelacionados('marca');
        }

        if($request->has('filtro')){
            $modeloRepository->filtro($request->filtro);
        }
        if($request->has('atributos')){
            $modeloRepository->selectAtributos($request->atributos);
        }

        return response()->json($modeloRepository->getResultadoPaginado(5), 200);
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

        $regras = $this->modelo->rules();
        //$feedback = $this->modelo->feedback();

        $request->validate($regras/*, $feedback*/);

        $imagem = $request->file('imagem');
        $imagem_path = $imagem->store('imagens/modelo','public');

        $modelo = $this->modelo->create([
            'marca_id'      => $request->marca_id,
            'nome'          => $request->nome,
            'imagem'        => $imagem_path,
            'numero_portas' => $request->numero_portas,
            'lugares'       => $request->lugares,
            'air_bag'       => $request->air_bag,
            'abs'           => $request->abs
        ]);

        return response()->json($modelo, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);

        if($modelo === null){
            return response()->json(['erro' => 'O recurso solicitado não existe'], 404);
        }

        return response()->json($modelo, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $modelo = $this->modelo->find($id);

        if($modelo === null){
            return response()->json(['erro' => 'Impossível atualizar. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH'){
            $regrasDinamicas = [];

            //Percorrendo todas as regras definidas no Model
            foreach ($modelo->rules() as $input => $regra) {

                //Coletar apenas as regras aplicavéis aos parâmetros da requisição
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }

        $request->validate($regrasDinamicas/*, $modelo->feedback()*/);

        }else{
        $request->validate($modelo->rules()/*, $modelo->feedback()*/);

        }
        //Remove uma imagem antiga e mantém a atual
        if($request->file('imagem')){
            Storage::disk('public')->delete($modelo->imagem);
        }

        $imagem = $request->file('imagem');
        $imagem_path = $imagem->store('imagens/modelo','public');
        /*
        $modelo->update([
            'marca_id'      => $request->marca_id,
            'nome'          => $request->nome,
            'imagem'        => $imagem_path,
            'numero_portas' => $request->numero_portas,
            'lugares'       => $request->lugares,
            'air_bag'       => $request->air_bag,
            'abs'           => $request->abs
        ]);
        */

        //Preencher o objeto marca com os dados do request
        $modelo->fill($request->all());
        $modelo->imagem = $imagem_path;
        $modelo->save();

        return response()->json($modelo, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);

        if($modelo === null){
            return response()->json(['erro' => 'Impossível Excluír. O recurso solicitado não existe'], 404);
        }
        //Remove a imagem
        Storage::disk('public')->delete($modelo->imagem);

        $modelo->delete();

        return response()->json(['msg' => 'modelo excluído com sucesso!'], 200);
    }
}

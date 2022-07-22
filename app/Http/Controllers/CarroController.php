<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Repositories\CarroRepository;
use Illuminate\Http\Request;

class CarroController extends Controller
{
    public function __construct(Carro $carro){
        $this->carro = $carro;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carroRepository = new CarroRepository($this->carro);

        if($request->has('atributos_modelo')){
            $atributos_modelo = 'modelo:id,'.$request->atributos_modelo;

            $carroRepository->selectAtributosRegistrosRelacionados($atributos_modelo);
        }else{
            $carroRepository->selectAtributosRegistrosRelacionados('modelo');
        }

        if($request->has('filtro')){
            $carroRepository->filtro($request->filtro);
        }
        if($request->has('atributos')){
            $carroRepository->selectAtributos($request->atributos);
        }

        return response()->json($carroRepository->getResultadoPaginado(5), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCarroRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $regras = $this->carro->rules();
        //$feedback = $this->modelo->feedback();

        $request->validate($regras/*, $feedback*/);


        $carro = $this->carro->create([
            'modelo_id'      => $request->modelo_id,
            'placa'          => $request->placa,
            'disponivel'     => $request->disponivel,
            'km'             => $request->km
        ]);

        return response()->json($carro, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carro = $this->carro->with('modelo')->find($id);

        if($carro === null){
            return response()->json(['erro' => 'O recurso solicitado não existe'], 404);
        }

        return response()->json($carro, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $carro = $this->carro->find($id);

        if($carro === null){
            return response()->json(['erro' => 'Impossível atualizar. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH'){
            $regrasDinamicas = [];

            //Percorrendo todas as regras definidas no Model
            foreach ($carro->rules() as $input => $regra) {

                //Coletar apenas as regras aplicavéis aos parâmetros da requisição
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }

        $request->validate($regrasDinamicas/*, $modelo->feedback()*/);

        }else{
        $request->validate($carro->rules()/*, $modelo->feedback()*/);

        }

        //Preencher o objeto marca com os dados do request
        $carro->fill($request->all());
        $carro->save();

        return response()->json($carro, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $carro = $this->carro->find($id);

        if($carro === null){
            return response()->json(['erro' => 'Impossível Excluír. O recurso solicitado não existe'], 404);
        }

        $carro->delete();

        return response()->json(['msg' => 'carro excluído com sucesso!'], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use App\Repositories\LocacaoRepository;
use Illuminate\Http\Request;

class LocacaoController extends Controller
{
    public function __construct(Locacao $locacao){
        $this->locacao = $locacao;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locacaoRepository = new LocacaoRepository($this->locacao);

        if($request->has('filtro')){
            $locacaoRepository->filtro($request->filtro);
        }
        if($request->has('atributos')){
            $locacaoRepository->selectAtributos($request->atributos);
        }

        return response()->json($locacaoRepository->getResultadoPaginado(5), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLocacaoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Pega a validação dos campos nome e imagem do Model

        $regras = $this->locacao->rules();
        //$feedback = $this->modelo->feedback();

        $request->validate($regras/*, $feedback*/);

        $locacao = $this->locacao->create([
            'cliente_id'                   => $request->cliente_id,
            'carro_id'                     => $request->carro_id,
            'data_inicio_periodo'          => $request->data_inicio_periodo,
            'data_final_previsto_periodo'  => $request->data_final_previsto_periodo,
            'data_final_realizado_periodo' => $request->data_final_realizado_periodo,
            'valor_diaria'                 => $request->valor_diaria,
            'km_inicial'                   => $request->km_inicial,
            'km_final'                     => $request->km_final
        ]);

        return response()->json($locacao, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $locacao = $this->locacao->find($id);

        if($locacao === null){
            return response()->json(['erro' => 'O recurso solicitado não existe'], 404);
        }

        return response()->json($locacao, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLocacaoRequest  $request
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $locacao = $this->locacao->find($id);

        if($locacao === null){
            return response()->json(['erro' => 'Impossível atualizar. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH'){
            $regrasDinamicas = [];

            //Percorrendo todas as regras definidas no Model
            foreach ($locacao->rules() as $input => $regra) {

                //Coletar apenas as regras aplicavéis aos parâmetros da requisição
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }

        $request->validate($regrasDinamicas/*, $modelo->feedback()*/);

        }else{
        $request->validate($locacao->rules()/*, $modelo->feedback()*/);

        }

        //Preencher o objeto locação com os dados do request
        $locacao->fill($request->all());
        $locacao->save();

        return response()->json($locacao, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $locacao = $this->locacao->find($id);

        if($locacao === null){
            return response()->json(['erro' => 'Impossível Excluír. O recurso solicitado não existe'], 404);
        }

        $locacao->delete();

        return response()->json(['msg' => 'locação excluído com sucesso!'], 200);
    }
}

<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                <!-- INICIO DA BUSCA DE UMA LOCAÇÃO -->
                <card-component titulo="Procurar locação">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col mb-3">
                                <inputContainer-component id="inputID" titulo="ID" help-id="helpID" texto-ajuda="Informe o ID (Opcional).">
                                    <input type="text" class="form-control" id="inputID" aria-describedby="helpID" placeholder="ID do cliente" v-model="buscar.id">
                                </inputContainer-component>
                            </div>
                            <div class="col mb-3">
                                <inputContainer-component id="inputData" titulo="Data" help-id="helpData" texto-ajuda="Informe a Data de início do período (Opcional).">
                                    <input type="text" class="form-control" id="inputData" aria-describedby="helpData" placeholder="00/00/0000" v-model="buscar.data_inicio_periodo">
                                </inputContainer-component>
                            </div>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col">
                                <button type="submit" class="btn btn-primary btn-sm" @click="pesquisar()">Pesquisar</button>
                            </div>
                            <div class="col-10">
                                <button type="submit" class="btn btn-outline-primary btn-sm" @click="limpar()">Limpar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- FIM DA BUSCA DE UMA LOCAÇÃO -->

                <!-- INICIO DA LISTA DE LOCAÇÕES -->
                <card-component titulo="Lista de locacões">
                    <template v-slot:conteudo>
                        <table-component
                            :dadosLocacoes="locacoes.data"
                            :titulos="{
                                id: { titulo: 'Id', tipo: 'text'},
                                valor_diaria: { titulo: 'Valor diaria', tipo: 'text'},
                                km_inicial: { titulo: 'Km inicial', tipo: 'text'},
                                km_final: { titulo: 'Km final', tipo: 'text'},
                                created_at: { titulo: 'Data de cadastro', tipo: 'data'}
                            }">
                        </table-component>
                    </template>
                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li v-for="l, key in locacoes.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'">
                                        <a class="page-link" v-html="l.label" @click="paginacao(l)"></a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-primary btn-sm float-right" data-bs-toggle="modal" data-bs-target="#modalAdicionar">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- FIM DO CARD DE LISTA DE LOCAÇÕES -->

                <!-- INICIO MODAL DE ADICIONAR LOCAÇÕES -->
                <modal-component id="modalAdicionar" titulo="Adicionar locação">
                    <template v-slot:alertas>
                        <alert-component v-if="$store.state.transacao.status == 'sucesso'" tipo="success" :detalhes="$store.state.transacao.mensagem" titulo="Registro adicionado com sucesso"></alert-component>
                        <alert-component v-if="$store.state.transacao.status == 'erro'" tipo="danger"  :detalhes="$store.state.transacao.mensagem" titulo="Erro ao adicionar registro"></alert-component>
                    </template>
                    <template v-slot:conteudo>
                        <div class="form-group">
                            <inputContainer-component id="Cliente_id" titulo="Cliente">
                                <select class="form-select" v-model="Cliente_id"  v-if="clientes.data != ''">
                                    <option value="">-- Selecione --</option>          
                                    <option v-for="cliente in clientes.data" :key="cliente.id" :value="cliente.id">{{ cliente.nome }}</option>
                                </select>
                            </inputContainer-component>
                        </div>
                        <div class="form-group">
                            <inputContainer-component id="Carro_id" titulo="Carro">
                                <select class="form-select" v-model="Carro_id" v-if="carros.data != ''">
                                    <option value="">-- Selecione --</option>          
                                    <option v-for="carro in carros.data" :key="carro.id" :value="carro.id">{{ carro.placa }}</option>
                                </select>
                            </inputContainer-component>
                        </div>
                        <div class="form-group">
                            <inputContainer-component id="Data inicio periodo" titulo="Data inicio periodo">
                                <input type="text" class="form-control mb-2" id="data_inicio_periodo" placeholder="00/00/0000" v-model="dataInicioPeriodo">
                            </inputContainer-component>
                        </div>
                        <div class="form-group">
                            <inputContainer-component id="Data final previsto periodo" titulo="Data final previsto periodo">
                                <input type="text" class="form-control mb-2" id="data_final previsto_periodo" placeholder="00/00/0000" v-model="dataFinalPrevistoPeriodo">
                            </inputContainer-component>
                        </div>
                        <div class="form-group">
                            <inputContainer-component id="Data final realizado periodo" titulo="Data final realizado periodo">
                                <input type="text" class="form-control mb-2" id="data_final realizado_periodo" placeholder="00/00/0000" v-model="dataFinalRealizadoPeriodo">
                            </inputContainer-component>
                        </div>
                        <div class="form-group">
                            <inputContainer-component id="Valor diaria" titulo="Valor diaria">
                                <label for="valor_diaria" class="form-label">Valor diaria</label>
                                <input type="text" class="form-control mb-2" id="valor_diaria" placeholder="Digite o valor da diária" v-model="valorDiaria">
                            </inputContainer-component>
                        </div>
                        <div class="form-group">
                            <inputContainer-component id="km_inicial" titulo="Km inicial">
                                <label for="km_inicial" class="form-label">Km inicial</label>
                                <input type="text" class="form-control mb-2" id="km_inicial" placeholder="Digite o Quilômetro inicial" v-model="kmInicial">
                            </inputContainer-component>
                        </div>
                        <div class="form-group">
                            <inputContainer-component id="km_final" titulo="Km final">
                                <input type="text" class="form-control mb-2" id="km_final" placeholder="Digite o Quilômetro final" v-model="kmFinal">
                            </inputContainer-component>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
                    </template>
                </modal-component>
                <!-- FIM MODAL DE ADICIONAR LOCAÇÃO -->

                <!-- INÍCIO MODAL DE VISUALIZAR LOCAÇÃO -->
                <modal-component id="modalVisualizar" titulo="Visualizar locação">
                    <template v-slot:conteudo>
                            <inputContainer-component titulo="ID">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.id" disabled>
                            </inputContainer-component>

                            <inputContainer-component titulo="Cliente_ID">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.cliente_id" disabled>
                            </inputContainer-component>

                            <inputContainer-component titulo="Carro_ID">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.carro_id" disabled>
                            </inputContainer-component>

                            <inputContainer-component titulo="Valor diária">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.valor_diaria" disabled>
                            </inputContainer-component>

                            <inputContainer-component titulo="Km inicial">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.km_inicial" disabled>
                            </inputContainer-component>

                            <inputContainer-component titulo="Km final">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.km_final" disabled>
                            </inputContainer-component>

                            <inputContainer-component titulo="Data início período">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.data_inicio_periodo | formataData" disabled>
                            </inputContainer-component>

                            <inputContainer-component titulo="Data final previsto período">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.data_final_previsto_periodo | formataData" disabled>
                            </inputContainer-component>

                            <inputContainer-component titulo="Data final realizado periodo">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.data_final_realizado_periodo | formataData" disabled>
                            </inputContainer-component>

                            <inputContainer-component titulo="Data de cadastro">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.created_at | formataData" disabled>
                            </inputContainer-component>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </template>
                </modal-component>
                <!-- FIM MODAL DE VISUALIZAR LOCAÇÃO -->

                <!-- INÍCIO MODAL DE REMOVER LOCAÇÃO -->
                <modal-component id="modalRemover" titulo="Remover cliente">
                    <template v-slot:alertas>
                        <alert-component v-if="$store.state.transacao.status == 'sucesso'" tipo="success" :detalhes="$store.state.transacao" titulo="Registro removido com sucesso"></alert-component>
                        <alert-component v-if="$store.state.transacao.status == 'erro'" tipo="danger"  :detalhes="$store.state.transacao" titulo="Erro ao remover registro"></alert-component>
                    </template>
                    <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                            <inputContainer-component titulo="ID">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.id" disabled>
                            </inputContainer-component>

                            <inputContainer-component titulo="Cliente_ID">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.cliente_id" disabled>
                            </inputContainer-component>

                            <inputContainer-component titulo="Carro_ID">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.carro_id" disabled>
                            </inputContainer-component>

                            <inputContainer-component titulo="Valor diária">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.valor_diaria" disabled>
                            </inputContainer-component>

                            <inputContainer-component titulo="Km inicial">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.km_inicial" disabled>
                            </inputContainer-component>

                            <inputContainer-component titulo="Km final">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.km_final" disabled>
                            </inputContainer-component>

                            <inputContainer-component titulo="Data início período">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.data_inicio_periodo " disabled>
                            </inputContainer-component>

                            <inputContainer-component titulo="Data final previsto período">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.data_final_previsto_periodo | formataData" disabled>
                            </inputContainer-component>

                            <inputContainer-component titulo="Data final realizado periodo">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.data_final_realizado_periodo | formataData" disabled>
                            </inputContainer-component>

                            <inputContainer-component titulo="Data de cadastro">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.created_at | formataData" disabled>
                            </inputContainer-component>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-danger" @click="remover()" v-if="$store.state.transacao.status != 'sucesso'">Excluir</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </template>
                </modal-component>
                <!-- FIM MODAL DE REMOVER LOCAÇÃO -->

                <!-- INÍCIO MODAL DE ATUALIZAR LOCAÇÃO -->
                <modal-component id="modalEditar" titulo="Atualizar locação">
                    <template v-slot:alertas>
                        <alert-component v-if="$store.state.transacao.status == 'sucesso'" tipo="success" :detalhes="$store.state.transacao" titulo="Registro atualizado com sucesso"></alert-component>
                        <alert-component v-if="$store.state.transacao.status == 'erro'" tipo="danger"  :detalhes="$store.state.transacao" titulo="Erro ao atualizar registro"></alert-component>
                    </template>
                    <template v-slot:conteudo>
                        <div class="form-group">
                            <inputContainer-component id="atualizarClienteID" titulo="Cliente ID">
                                <select class="form-select" v-model="Cliente_id"  v-if="clientes.data != ''">
                                    <option value="">-- Selecione --</option>          
                                    <option v-for="cliente in clientes.data" :key="cliente.id" :value="cliente.id">{{ cliente.nome }}</option>
                                </select>
                            </inputContainer-component>
                        </div>
                        <div class="form-group">
                            <inputContainer-component id="atualizarCarroID" titulo="Carro ID">
                                <select class="form-select" v-model="Carro_id" v-if="carros.data != ''">
                                    <option value="">-- Selecione --</option>          
                                    <option v-for="carro in carros.data" :key="carro.id" :value="carro.id">{{ carro.placa }}</option>
                                </select>
                            </inputContainer-component>
                        </div>
                        <div class="form-group">
                            <inputContainer-component id="AtualizarValorDiaria">
                                <input type="text" class="form-control mb-2" v-model="$store.state.item.valor_diaria">
                            </inputContainer-component>
                        </div>
                        <div class="form-group">
                            <inputContainer-component titulo="Km inicial">
                                <input type="text" class="form-control mb-2" v-model="$store.state.item.km_inicial">
                            </inputContainer-component>
                        </div>
                        <div class="form-group">
                            <inputContainer-component titulo="Km final">
                                <input type="text" class="form-control mb-2" v-model="$store.state.item.km_final">
                            </inputContainer-component>                        
                        </div>
                        <div class="form-group">
                            <inputContainer-component titulo="Data início período">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.data_inicio_periodo | dataParaLocacoes">
                            </inputContainer-component>
                        </div>
                        <div class="form-group">
                            <inputContainer-component titulo="Data final previsto período">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.data_final_previsto_periodo | dataParaLocacoes">
                            </inputContainer-component>                        
                        </div>
                        <div class="form-group">
                            <inputContainer-component titulo="Data final realizado periodo">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.data_final_realizado_periodo | dataParaLocacoes">
                            </inputContainer-component>                        
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
                    </template>
                </modal-component>
                <!-- FIM MODAL DE ATUALIZAR LOCAÇÃO -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Paginate from './Paginate.vue'

    export default{
        components: { Paginate },
        data(){
            return {
                urlBase: 'http://localhost:8000/api/v1/locacao',
                urlClientes: 'http://localhost:8000/api/v1/cliente',
                urlCarros: 'http://localhost:8000/api/v1/carro',
                urlPage: '',
                urlFiltro: '',
                transacaoStatus: '',
                Cliente_id: '',
                Carro_id: '',
                dataInicioPeriodo: '',
                dataFinalPrevistoPeriodo: '',
                dataFinalRealizadoPeriodo: '',
                valorDiaria: '',
                kmInicial: '',
                kmFinal: '',
                transacaoDetalhes: [],
                locacoes: { data:[] },
                carros: { data:[] },
                clientes: { data:[] },
                buscar: {
                    id: '',
                    data_inicio_periodo: ''
                }
            }
        },
        methods: {
            atualizar(){
                let url = this.urlBase + '/' + this.$store.state.item.id
                let formData = new FormData()
                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }

                formData.append('_method', 'patch')
                formData.append('cliente_id', this.$store.state.item.cliente_id)
                formData.append('carro_id', this.$store.state.item.carro_id)
                formData.append('data_inicio_periodo', this.$store.state.item.data_inicio_periodo)
                formData.append('data_final_previsto_periodo', this.$store.state.item.data_final_previsto_periodo)
                formData.append('data_final_realizado_periodo', this.$store.state.item.data_final_realizado_periodo)
                formData.append('valor_diaria', this.$store.state.item.valor_diaria)
                formData.append('km_inicial', this.$store.state.item.km_inicial)
                formData.append('km_final', this.$store.state.item.km_final)

                axios.post(url,formData,config)
                     .then(response => {

                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = response.data.msg
                        this.carregarLista()

                     })
                     .catch(errors => {

                        this.$store.state.transacao.status = 'erro'
                        //this.$store.state.transacao.mensagem = errors.response.data.errors
                        this.$store.state.transacao.dados = errors.response.data.errors

                     })
            },
            remover(){
                let confirmacao = confirm('Quer mesmo remover o registro?')

                if(!confirmacao){
                    return false
                }

                let url = this.urlBase + '/' + this.$store.state.item.id
                let formData = new FormData()

                formData.append('_method', 'delete')

                axios.post(url,formData)
                     .then(response => {

                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = response.data.msg
                        this.carregarLista()

                     })
                     .catch(errors => {

                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.erro

                     })
            },
            paginacao(l){
                if(l.url){
                    this.urlPage = l.url.split('?')[1]
                    this.carregarLista()
                }
            },
            pesquisar(){
                let filtro = ''

                for(let chave in this.buscar){
                    if(this.buscar[chave]){
                        if(filtro != ''){
                            filtro += ';'
                        }
                        filtro += chave + ':like:' + this.buscar[chave]
                    }
                }

                if(filtro != ''){
                    this.urlPage = 'page=1'
                    this.urlFiltro = '&filtro=' + filtro
                }else{
                    this.urlFiltro = ''
                }

                this.carregarLista()
            },
            limpar(){
                document.location.reload(true)
            },
            carregarLista(){
                let url         = this.urlBase + '?' + this.urlPage + this.urlFiltro
                let urlClientes = this.urlClientes + '?' + this.urlPage + this.urlFiltro
                let urlCarros   = this.urlCarros + '?' + this.urlPage + this.urlFiltro

                axios.get(url)
                .then(response => {
                    this.locacoes = response.data

                    axios.get(urlClientes)
                        .then(response => {
                            this.clientes = response.data

                            axios.get(urlCarros)
                                .then(response => {
                                    this.carros = response.data

                                })
                        })
                })
                .catch(errors => {
                    console.log(errors)
                })
            },
            salvar(){
                let formData = new FormData()

                formData.append('cliente_id', this.Cliente_id)
                formData.append('carro_id', this.Carro_id)
                formData.append('data_inicio_periodo', this.dataInicioPeriodo)
                formData.append('data_final_previsto_periodo', this.dataFinalPrevistoPeriodo)
                formData.append('data_final_realizado_periodo', this.dataFinalRealizadoPeriodo)
                formData.append('valor_diaria', this.valorDiaria)
                formData.append('km_inicial', this.kmInicial)
                formData.append('km_final', this.kmFinal)

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }

                axios.post(
                    this.urlBase,
                    formData,
                    config
                )
                .then(response => {
                    this.$store.state.transacao.status = 'sucesso'
                    this.$store.state.transacao.mensagem = response.data.msg
                    document.location.reload(true)

                })
                .catch(errors => {
                    this.$store.state.transacao.status = 'erro'
                    this.$store.state.transacao.mensagem = errors.data.message
                    console.log(errors.data.message)
                })
            }
        },
        mounted(){
            this.carregarLista()
        }
    }
</script>


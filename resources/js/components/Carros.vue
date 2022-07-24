<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- INICIO DA BUSCA DE MARCAS -->
                <card-component titulo="Buscar carros">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col mb-3">
                                <inputContainer-component id="inputId" titulo="ID" help-id="helpID" texto-ajuda="Informe o ID (Opcional).">
                                    <input type="number" class="form-control" id="inputID" aria-describedby="helpID" placeholder="ID" v-model="buscar.id">
                                </inputContainer-component>
                            </div>
                            <div class="col mb-3">
                                <inputContainer-component id="inputPlaca" titulo="Placa" help-id="helpPlaca" texto-ajuda="Informe a Placa (Opcional).">
                                    <input type="text" class="form-control" id="inputPlaca" aria-describedby="helpPlaca" placeholder="Placa do carro" v-model="buscar.placa">
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
                <!-- FIM DA BUSCA DE MARCAS -->
                <!-- INICIO DA LISTAGEM DE MARCAS -->
                <card-component titulo="Listagem de carros">
                    <template v-slot:conteudo>
                        <table-component 
                            :dadosCarros="carros.data" 
                            :titulos="{
                                id: { titulo: 'Id', tipo: 'text'},
                                placa: { titulo: 'Placa', tipo: 'text'},
                                disponivel: { titulo: 'Disponível', tipo: 'text'},
                                created_at: { titulo: 'Data de cadastro', tipo: 'data'},
                            }">
                        </table-component>
                    </template>
                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li v-for="l, key in carros.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'">
                                        <a class="page-link" v-html="l.label" @click="paginacao(l)"></a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-primary btn-sm float-right" data-bs-toggle="modal" data-bs-target="#modalCarros">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- FIM DO CARD DE LISTAGEM DE MARCAS -->
            </div>
        </div>
        <!-- MODAL -->
        <modal-component id="modalCarros" titulo="Adicionar carro">
            <template v-slot:alertas>
                <alert-component tipo="success" v-if="transacaoStatus == 'adicionado'" :detalhes="transacaoDetalhes" titulo="Carro adicionado com sucesso"></alert-component>
                <alert-component tipo="danger"  v-if="transacaoStatus == 'erro'" :detalhes="transacaoDetalhes" titulo="Erro ao adicionar"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="form-group">
                    <inputContainer-component id="placa" titulo="placa">
                        <label for="placa" class="form-label">Placa</label>
                        <input type="text" class="form-control mb-2" id="placa" placeholder="Placa do carro" v-model="placa">
                    </inputContainer-component>
                </div>
                <div class="form-group">
                    <inputContainer-component id="modelo_id" titulo="Modelo ID">
                        <input type="number" min="1" class="form-control mb-2" id="modelo_id" placeholder="Modelo do carro" v-model="modelo_id">
                    </inputContainer-component>
                </div>
                <div class="form-group">
                    <inputContainer-component id="disponivel" titulo="disponivel">
                        <input type="number" min="0" max="1" class="form-control" id="disponivel" placeholder="0 é não, 1 é sim" v-model="disponivel">
                    </inputContainer-component>
                </div>
                <div class="form-group">
                    <inputContainer-component id="km" titulo="quilometro">
                        <input type="number" min="0" class="form-control mb-2" id="km" placeholder="Km/h" v-model="km">
                    </inputContainer-component>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>
    </div>
</template>

<script>
    import Paginate from './Paginate.vue'

    export default{
        components: { Paginate },
        computed: {
            token(){
                let token = document.cookie.split(';').find(indice => {
                    return indice.includes('token=')
                })

                token = token.split('=')[1]
                token = 'Bearer ' + token
                
                return token
            }   
        },
        data(){
            return {
                urlBase: 'http://localhost:8000/api/v1/carro',
                urlPage: '',
                urlFiltro: '',
                transacaoStatus: '',
                placa: '',
                modelo_id: '',
                disponivel: '',
                km: '',
                transacaoDetalhes: [],
                carros: { data:[] },
                buscar: {
                    id: '',
                    placa: ''
                }
            }
        },
        methods: {
            paginacao(l){
                if(l.url){
                    this.urlBase = l.url
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
                let url = this.urlBase + '?' + this.urlPage + this.urlFiltro

                axios.get(url)
                .then(response => {
                    this.carros = response.data
                    console.log(this.carros)
                })
                .catch(errors => {
                    console.log(errors)
                })
            },
            salvar(){
                let formData = new FormData()

                formData.append('placa', this.placa)
                formData.append('modelo_id', this.modelo_id)
                formData.append('disponivel', this.disponivel)
                formData.append('km', this.km)

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.post(
                    this.urlBase,
                    formData,
                    config
                )
                .then(response => {
                    this.transacaoStatus = 'adicionado'
                    this.transacaoDetalhes = {
                        mensagem: 'ID do carro:' + response.data.id
                    }
                    document.location.reload(true)
                })
                .catch(errors => {
                    this.transacaoStatus = 'erro'
                    this.transacaoDetalhes = {
                        mensagem: errors.response.data.message,
                        dados: errors.response.data.errors
                    }
                    
                })
            }
        },
        mounted(){
            this.carregarLista()
        }
    }
</script>

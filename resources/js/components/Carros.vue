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
                                <button type="button" class="btn btn-primary btn-sm float-right" data-bs-toggle="modal" data-bs-target="#modalAdicionar">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- FIM DO CARD DE LISTAGEM DE MARCAS -->
            </div>
        </div>
        <!-- INICIO MODAL DE ADICIONAR CARRO -->
        <modal-component id="modalAdicionar" titulo="Adicionar carro">
            <template v-slot:alertas>
                <alert-component v-if="$store.state.transacao.status == 'sucesso'" tipo="success" :detalhes="$store.state.transacao" titulo="Registro adicionado com sucesso"></alert-component>
                <alert-component v-if="$store.state.transacao.status == 'erro'" tipo="danger"  :detalhes="$store.state.transacao" titulo="Erro ao adicionar registro"></alert-component>
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
        <!-- FIM MODAL DE ADICIONAR CARRO -->

        <!-- INÍCIO MODAL DE VISUALIZAR CARRO -->
        <modal-component id="modalVisualizar" titulo="Visualizar carro">
            <template v-slot:conteudo>
                    <inputContainer-component titulo="ID">
                        <input type="text" class="form-control mb-2" :value="$store.state.item.id" disabled>
                    </inputContainer-component>

                    <inputContainer-component titulo="Carro">
                        <input type="text" class="form-control mb-2" :value="$store.state.item.placa" disabled>
                    </inputContainer-component>

                    <inputContainer-component titulo="Data de cadastro">
                        <input type="text" class="form-control mb-2" :value="$store.state.item.created_at" disabled>
                    </inputContainer-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </template>
        </modal-component>
        <!-- FIM MODAL DE VISUALIZAR CARRO -->

        <!-- INÍCIO MODAL DE REMOVER CARRO -->
        <modal-component id="modalRemover" titulo="Remover carro">
            <template v-slot:alertas>
                <alert-component v-if="$store.state.transacao.status == 'sucesso'" tipo="success" :detalhes="$store.state.transacao" titulo="Registro removido com sucesso"></alert-component>
                <alert-component v-if="$store.state.transacao.status == 'erro'" tipo="danger"  :detalhes="$store.state.transacao" titulo="Erro ao remover registro"></alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                    <inputContainer-component titulo="ID">
                        <input type="text" class="form-control mb-2" :value="$store.state.item.id" disabled>
                    </inputContainer-component>

                    <inputContainer-component titulo="Carro">
                        <input type="text" class="form-control mb-2" :value="$store.state.item.placa" disabled>
                    </inputContainer-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-danger" @click="remover()" v-if="$store.state.transacao.status != 'sucesso'">Excluir</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </template>
        </modal-component>
        <!-- FIM MODAL DE REMOVER CARRO -->

        <!-- INÍCIO MODAL DE ATUALIZAR CARRO -->
        <modal-component id="modalEditar" titulo="Atualizar carro">
            <template v-slot:alertas>
                <alert-component v-if="$store.state.transacao.status == 'sucesso'" tipo="success" :detalhes="$store.state.transacao" titulo="Registro atualizado com sucesso"></alert-component>
                <alert-component v-if="$store.state.transacao.status == 'erro'" tipo="danger"  :detalhes="$store.state.transacao" titulo="Erro ao atualizar registro"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="form-group">
                    <inputContainer-component id="atualizarPlaca">
                        <input type="text" class="form-control mb-2" id="atualizarPlaca" placeholder="Placa do carro" v-model="$store.state.item.placa">
                    </inputContainer-component>
                </div>
                <div class="form-group">
                    <inputContainer-component id="AtualizaDisponibilidade">
                        <input type="number" min="0" max="1" class="form-control" id="AtualizaDisponibilidade" placeholder="0 é não, 1 é sim" v-model="$store.state.item.disponivel">
                    </inputContainer-component>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
            </template>
        </modal-component>
        <!-- FIM MODAL DE ATUALIZAR CARRO -->
    </div>
</template>

<script>
    import Paginate from './Paginate.vue'

    export default{
        components: { Paginate },
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
            atualizar(){
                let url = this.urlBase + '/' + this.$store.state.item.id
                let formData = new FormData()
                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }

                formData.append('_method', 'patch')
                formData.append('placa', this.$store.state.item.placa)
                formData.append('disponivel', this.$store.state.item.disponivel)

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
                        document.location.reload(true)

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
                    this.$store.state.transacao.mensagem = errors.data.msg
                    
                })
            }
        },
        mounted(){
            this.carregarLista()
        }
    }
</script>

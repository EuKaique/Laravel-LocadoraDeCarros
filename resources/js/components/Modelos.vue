<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- INICIO DA BUSCA DE MARCAS -->
                <card-component titulo="Buscar modelos">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col mb-3">
                                <inputContainer-component id="inputId" titulo="ID" help-id="helpID" texto-ajuda="Informe o ID (Opcional)." v-model="buscar.id">
                                    <input type="number" class="form-control" id="inputID" aria-describedby="helpID" placeholder="ID">
                                </inputContainer-component>
                            </div>
                            <div class="col mb-3">
                                <inputContainer-component id="inputNome" titulo="Nome" help-id="helpNome" texto-ajuda="Informe o Nome (Opcional).">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="helpNome" placeholder="Nome do modelo" v-model="buscar.nome">
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
                <card-component titulo="Listagem de modelos">
                    <template v-slot:conteudo>
                        <table-component 
                            :dados-modelos="modelos.data" 
                            :titulos="{
                                id: { titulo: 'Id', tipo: 'text'},
                                nome: { titulo: 'Nome', tipo: 'text'},
                                imagem: { titulo: 'Imagem', tipo: 'imagem'},
                                numero_portas: { titulo: 'Qtd portas', tipo: 'text'},
                                air_bag: { titulo: 'Air bag', tipo: 'text'},
                                created_at: { titulo: 'Data de cadastro', tipo: 'data'},
                            }">
                        </table-component>
                    </template>
                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li v-for="l, key in modelos.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'">
                                        <a class="page-link" v-html="l.label" @click="paginacao(l)"></a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-primary btn-sm float-right" data-bs-toggle="modal" data-bs-target="#modalModelos">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- FIM DO CARD DE LISTAGEM DE MARCAS -->
            </div>
        </div>
        <!-- MODAL -->
        <modal-component id="modalModelos" titulo="Adicionar modelo">
            <template v-slot:alertas>
                <alert-component tipo="success" v-if="transacaoStatus == 'adicionado'" :detalhes="transacaoDetalhes" titulo="Modelo adicionado com sucesso"></alert-component>
                <alert-component tipo="danger"  v-if="transacaoStatus == 'erro'" :detalhes="transacaoDetalhes" titulo="Erro ao adicionar"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="form-group">
                    <inputContainer-component id="marca_id" titulo="Marca">
                        <input type="text" class="form-control mb-2" id="marca_id" placeholder="ID da marca" v-model="marca_id">
                    </inputContainer-component>
                </div>
                <div class="form-group">
                    <inputContainer-component id="nome" titulo="Nome">
                        <input type="text" class="form-control mb-2" id="nome" placeholder="Nome do modelo" v-model="nome">
                    </inputContainer-component>
                </div>
                <div class="form-group">
                    <inputContainer-component id="imagem" titulo="Imagem">
                        <input type="file" class="form-control-file mt-2" id="imagem" @change="carregarImagem($event)">
                    </inputContainer-component>
                </div>
                <div class="form-group">
                    <inputContainer-component id="numero_portas" titulo="Qtd portas">
                        <input type="text" class="form-control mb-2" id="numero_portas" placeholder="Quantidade de portas" v-model="numero_portas">
                    </inputContainer-component>
                </div>
                <div class="form-group">
                    <inputContainer-component id="lugares" titulo="Lugares">
                        <input type="text" class="form-control mb-2" id="lugares" placeholder="Lugares" v-model="lugares">
                    </inputContainer-component>
                </div>
                <div class="form-group">
                    <inputContainer-component id="air_bag" titulo="Air bag">
                        <input type="text" class="form-control mb-2" id="air_bag" placeholder="Tem air bag? 0 não, 1 Sim" v-model="air_bag">
                    </inputContainer-component>
                </div>
                <div class="form-group">
                    <inputContainer-component id="abs" titulo="Abs">
                        <input type="text" class="form-control mb-2" id="abs" placeholder="Tem abs? 0 não, 1 Sim" v-model="abs">
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
                urlBase: 'http://localhost:8000/api/v1/modelo',
                urlPage: '',
                urlFiltro: '',
                nome: '',
                arquivoImagem: [],
                numero_portas: '',
                lugares: '',
                air_bag: '',
                abs: '',
                transacaoStatus: '',
                transacaoDetalhes: [],
                modelos: { data:[] },
                buscar: {
                    id: '',
                    nome: ''
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
                    this.modelos = response.data
                    console.log(this.modelos)
                })
                .catch(errors => {
                    console.log(errors)
                })
            },
            carregarImagem(e){
                this.arquivoImagem = e.target.files
            },
            salvar(){
                let formData = new FormData()

                formData.append('marca_id', this.marca_id)
                formData.append('nome', this.nome)
                formData.append('imagem', this.arquivoImagem[0])
                formData.append('numero_portas', this.numero_portas)
                formData.append('lugares', this.lugares)
                formData.append('air_bag', this.air_bag)
                formData.append('abs', this.abs)

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
                        mensagem: 'ID do modelo:' + response.data.id
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

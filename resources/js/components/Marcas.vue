<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- INICIO DA BUSCA DE MARCAS -->
                <card-component titulo="Buscar marcas">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col mb-3">
                                <inputContainer-component id="inputId" titulo="ID" help-id="helpID" texto-ajuda="Informe o ID (Opcional).">
                                    <input type="number" class="form-control" id="inputID" aria-describedby="helpID" placeholder="ID" v-model="buscar.id">
                                </inputContainer-component>
                            </div>
                            <div class="col mb-3">
                                <inputContainer-component id="inputNome" titulo="Nome" help-id="helpNome" texto-ajuda="Informe o Nome (Opcional).">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="helpNome" placeholder="Nome da marca" v-model="buscar.nome">
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
                <card-component titulo="Listagem de marcas">
                    <template v-slot:conteudo>
                        <table-component 
                            :dadosMarcas="marcas.data" 
                            :titulos="{
                                id: { titulo: 'Id', tipo: 'text'},
                                nome: { titulo: 'Nome', tipo: 'text'},
                                imagem: { titulo: 'Imagem', tipo: 'imagem'},
                                created_at: { titulo: 'Data de cadastro', tipo: 'data'},
                            }">
                        </table-component>
                    </template>
                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li v-for="l, key in marcas.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'">
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
        <!-- INÍCIO MODAL DE ADICIONAR MARCA -->
        <modal-component id="modalAdicionar" titulo="Adicionar marca">
            <template v-slot:alertas>
                <alert-component v-if="$store.state.transacao.status == 'sucesso'" tipo="success" :detalhes="$store.state.transacao" titulo="Registro adicionado com sucesso"></alert-component>
                <alert-component v-if="$store.state.transacao.status == 'erro'" tipo="danger"  :detalhes="$store.state.transacao" titulo="Erro ao adicionar registro"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="form-group">
                    <inputContainer-component id="novoNome">
                        <input type="text" class="form-control mb-2" id="novoNome" placeholder="Nome da marca" v-model="nomeMarca">
                    </inputContainer-component>
                </div>
                <div class="form-group">
                    <inputContainer-component id="novaImagem" titulo="Imagem">
                        <input type="file" class="form-control-file mt-2" id="novaImagem" @change="carregarImagem($event)">
                    </inputContainer-component>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>
        <!-- FIM MODAL DE ADICIONAR MARCA -->

        <!-- INÍCIO MODAL DE VISUALIZAR MARCA -->
        <modal-component id="modalVisualizar" titulo="Visualizar marca">
            <template v-slot:conteudo>
                    <inputContainer-component titulo="ID">
                        <input type="text" class="form-control mb-2" :value="$store.state.item.id" disabled>
                    </inputContainer-component>

                    <inputContainer-component titulo="Marca">
                        <input type="text" class="form-control mb-2" :value="$store.state.item.nome" disabled>
                    </inputContainer-component>

                    <inputContainer-component>
                        <img :src="'storage/'+$store.state.item.imagem" v-if="$store.state.item.imagem">
                    </inputContainer-component>

                    <inputContainer-component titulo="Data de cadastro">
                        <input type="text" class="form-control mb-2" :value="$store.state.item.created_at | formataData" disabled>
                    </inputContainer-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </template>
        </modal-component>
        <!-- FIM MODAL DE VISUALIZAR MARCA -->

        <!-- INÍCIO MODAL DE REMOVER MARCA -->
        <modal-component id="modalRemover" titulo="Remover marca">
            <template v-slot:alertas>
                <alert-component v-if="$store.state.transacao.status == 'sucesso'" tipo="success" :detalhes="$store.state.transacao" titulo="Registro removido com sucesso"></alert-component>
                <alert-component v-if="$store.state.transacao.status == 'erro'" tipo="danger"  :detalhes="$store.state.transacao" titulo="Erro ao remover registro"></alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                    <inputContainer-component titulo="ID">
                        <input type="text" class="form-control mb-2" :value="$store.state.item.id" disabled>
                    </inputContainer-component>

                    <inputContainer-component titulo="Marca">
                        <input type="text" class="form-control mb-2" :value="$store.state.item.nome" disabled>
                    </inputContainer-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-danger" @click="remover()" v-if="$store.state.transacao.status != 'sucesso'">Excluir</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </template>
        </modal-component>
        <!-- FIM MODAL DE REMOVER MARCA -->

        <!-- INÍCIO MODAL DE ATUALIZAR MARCA -->
        <modal-component id="modalEditar" titulo="Atualizar marca">
            <template v-slot:alertas>
                <alert-component v-if="$store.state.transacao.status == 'sucesso'" tipo="success" :detalhes="$store.state.transacao" titulo="Registro atualizado com sucesso"></alert-component>
                <alert-component v-if="$store.state.transacao.status == 'erro'" tipo="danger"  :detalhes="$store.state.transacao" titulo="Erro ao atualizar registro"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="form-group">
                    <inputContainer-component id="atualizarNome">
                        <input type="text" class="form-control mb-2" id="atualizarNome" placeholder="Nome da marca" v-model="$store.state.item.nome">
                    </inputContainer-component>
                </div>
                <div class="form-group">
                    <inputContainer-component id="atualizarImagem" titulo="Imagem">
                        <input type="file" class="form-control-file mt-2" id="atualizarImagem" @change="carregarImagem($event)">
                    </inputContainer-component>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
            </template>
        </modal-component>
        <!-- FIM MODAL DE ATUALIZAR MARCA -->
    </div>
</template>

<script>
    import Paginate from './Paginate.vue'

    export default{
        components: { Paginate },
        data(){
            return {
                urlBase: 'http://localhost:8000/api/v1/marca',
                urlPage: '',
                urlFiltro: '',
                nomeMarca: '',
                arquivoImagem: [],
                transacaoStatus: '',
                transacaoDetalhes: [],
                marcas: { data:[] },
                buscar: {
                    id: '',
                    nome: ''
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
                formData.append('nome', this.$store.state.item.nome)

                if(this.arquivoImagem[0]){
                    formData.append('imagem', this.arquivoImagem[0])
                }

                axios.post(url,formData,config)
                     .then(response => {

                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = response.data.msg

                        atualizarImagem.value = ''
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
                let url = this.urlBase + '?' + this.urlPage + this.urlFiltro
                axios.get(url)
                .then(response => {
                    this.marcas = response.data
                    console.log(this.marcas)
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

                formData.append('nome', this.nomeMarca)
                formData.append('imagem', this.arquivoImagem[0])

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

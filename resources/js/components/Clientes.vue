<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                <!-- INICIO DA BUSCA DE CLIENTES -->
                <card-component titulo="Procurar cliente">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col mb-3">
                                <inputContainer-component id="inputCPF" titulo="CPF" help-id="helpID" texto-ajuda="Informe o CPF (Opcional).">
                                    <input type="text" class="form-control" id="inputCPF" aria-describedby="helpCPF" placeholder="CPF do cliente" v-model="buscar.cpf">
                                </inputContainer-component>
                            </div>
                            <div class="col mb-3">
                                <inputContainer-component id="inpuNome" titulo="Nome" help-id="helpNome" texto-ajuda="Informe a Nome (Opcional).">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="helpNome" placeholder="Nome do cliente" v-model="buscar.nome">
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
                <!-- FIM DA BUSCA DE CLIENTES -->

                <!-- INICIO DA LISTA DE CLIENTES -->
                <card-component titulo="Lista de clientes">
                    <template v-slot:conteudo>
                        <table-component 
                            :dadosClientes="clientes.data" 
                            :titulos="{
                                id: { titulo: 'Id', tipo: 'text'},
                                nome: { titulo: 'Nome', tipo: 'text'},
                                cpf: { titulo: 'CPF', tipo: 'text'},
                                created_at: { titulo: 'Data de cadastro', tipo: 'data'},
                            }">
                        </table-component>
                    </template>
                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li v-for="l, key in clientes.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'">
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
                <!-- FIM DO CARD DE LISTA DE CLIENTES -->

                <!-- INICIO MODAL DE ADICIONAR CLIENTE -->
                <modal-component id="modalAdicionar" titulo="Adicionar cliente">
                    <template v-slot:alertas>
                        <alert-component v-if="$store.state.transacao.status == 'sucesso'" tipo="success" :detalhes="$store.state.transacao.mensagem" titulo="Registro adicionado com sucesso"></alert-component>
                        <alert-component v-if="$store.state.transacao.status == 'erro'" tipo="danger"  :detalhes="$store.state.transacao.mensagem" titulo="Erro ao adicionar registro"></alert-component>
                    </template>
                    <template v-slot:conteudo>
                        <div class="form-group">
                            <inputContainer-component id="nome">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control mb-2" id="nome" placeholder="Nome do cliente" v-model="nomeCliente">
                            </inputContainer-component>
                        </div>
                        <div class="form-group">
                            <inputContainer-component id="cpf">
                                <label for="cpf" class="form-label">CPF</label>
                                <input type="text" class="form-control mb-2" id="cpf" placeholder="CPF do cliente" v-model="cpfCliente" v-mask="'###.###.###-##'">
                            </inputContainer-component>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
                    </template>
                </modal-component>
                <!-- FIM MODAL DE ADICIONAR CLIENTE -->

                <!-- INÍCIO MODAL DE VISUALIZAR CLIENTE -->
                <modal-component id="modalVisualizar" titulo="Visualizar cliente">
                    <template v-slot:conteudo>
                            <inputContainer-component titulo="ID">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.id" disabled>
                            </inputContainer-component>

                            <inputContainer-component titulo="Cliente">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.nome" disabled>
                            </inputContainer-component>

                            <inputContainer-component titulo="CPF">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.cpf" disabled>
                            </inputContainer-component>

                            <inputContainer-component titulo="Data de cadastro">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.created_at | formataData" disabled>
                            </inputContainer-component>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </template>
                </modal-component>
                <!-- FIM MODAL DE VISUALIZAR CLIENTE -->

                <!-- INÍCIO MODAL DE REMOVER CLIENTE -->
                <modal-component id="modalRemover" titulo="Remover cliente">
                    <template v-slot:alertas>
                        <alert-component v-if="$store.state.transacao.status == 'sucesso'" tipo="success" :detalhes="$store.state.transacao" titulo="Registro removido com sucesso"></alert-component>
                        <alert-component v-if="$store.state.transacao.status == 'erro'" tipo="danger"  :detalhes="$store.state.transacao" titulo="Erro ao remover registro"></alert-component>
                    </template>
                    <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                            <inputContainer-component titulo="ID">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.id" disabled>
                            </inputContainer-component>

                            <inputContainer-component titulo="Nome">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.nome" disabled>
                            </inputContainer-component>

                            <inputContainer-component titulo="CPF">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.cpf" disabled>
                            </inputContainer-component>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-danger" @click="remover()" v-if="$store.state.transacao.status != 'sucesso'">Excluir</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </template>
                </modal-component>
                <!-- FIM MODAL DE REMOVER CLIENTE -->

                <!-- INÍCIO MODAL DE ATUALIZAR CLIENTE -->
                <modal-component id="modalEditar" titulo="Atualizar cliente">
                    <template v-slot:alertas>
                        <alert-component v-if="$store.state.transacao.status == 'sucesso'" tipo="success" :detalhes="$store.state.transacao" titulo="Registro atualizado com sucesso"></alert-component>
                        <alert-component v-if="$store.state.transacao.status == 'erro'" tipo="danger"  :detalhes="$store.state.transacao" titulo="Erro ao atualizar registro"></alert-component>
                    </template>
                    <template v-slot:conteudo>
                        <div class="form-group">
                            <inputContainer-component id="atualizarNome">
                                <input type="text" class="form-control mb-2" id="atualizarNome" placeholder="Nome do cliente" v-model="$store.state.item.nome">
                            </inputContainer-component>
                        </div>
                        <div class="form-group">
                            <inputContainer-component id="atualizarCpf">
                                <input type="text" class="form-control mb-2" id="atualizarCpf" placeholder="Cpf do cliente" v-model="$store.state.item.cpf" v-mask="'###.###.###-##'">
                            </inputContainer-component>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
                    </template>
                </modal-component>
                <!-- FIM MODAL DE ATUALIZAR CLIENTE -->
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
                urlBase: 'http://localhost:8000/api/v1/cliente',
                urlPage: '',
                urlFiltro: '',
                transacaoStatus: '',
                nomeCliente: '',
                cpfCliente: '',
                transacaoDetalhes: [],
                clientes: { data:[] },
                buscar: {
                    nome: '',
                    cpf: ''
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
                formData.append('cpf', this.$store.state.item.cpf)

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
                let url = this.urlBase + '?' + this.urlPage + this.urlFiltro

                axios.get(url)
                .then(response => {
                    this.clientes = response.data
                    console.log(this.clientes)
                })
                .catch(errors => {
                    console.log(errors)
                })
            },
            salvar(){
                let formData = new FormData()

                formData.append('nome', this.nomeCliente)
                formData.append('cpf', this.cpfCliente)

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

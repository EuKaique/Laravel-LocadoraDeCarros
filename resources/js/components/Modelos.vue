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
                                <button type="button" class="btn btn-primary btn-sm float-right" data-bs-toggle="modal" data-bs-target="#modalAdicionar">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- FIM DO CARD DE LISTAGEM DE MARCAS -->
            </div>
        </div>
        <!-- INICIO MODAL DE ADICIONAR MODELO -->
        <modal-component id="modalAdicionar" titulo="Adicionar modelo">
            <template v-slot:alertas>
                <alert-component v-if="$store.state.transacao.status == 'sucesso'" tipo="success" :detalhes="$store.state.transacao" titulo="Registro adicionado com sucesso"></alert-component>
                <alert-component v-if="$store.state.transacao.status == 'erro'" tipo="danger"  :detalhes="$store.state.transacao" titulo="Erro ao adicionar registro"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="form-group">
                    <inputContainer-component id="marca_id" titulo="Marca">
                        <select class="form-select" v-model="marca_id" v-if="marcas.data != ''">
                            <option value="">Selecione uma marca</option>
                            <option v-for="mi in marcas.data" :key="mi.id" :value="mi.id">{{ mi.nome }}</option>
                        </select>
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
                        <select class="form-select" v-model="air_bag" v-if="modelos.data != ''">
                            <option value="Sim">Sim</option>
                            <option value="Não">Não</option>
                        </select>
                    </inputContainer-component>
                </div>
                <div class="form-group">
                    <inputContainer-component id="abs" titulo="Abs">
                        <select class="form-select" v-model="abs" v-if="modelos.data != ''">
                            <option value="Sim">Sim</option>
                            <option value="Não">Não</option>
                        </select>
                    </inputContainer-component>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>
        <!-- FIM MODAL DE ADICIONAR MODELO -->

        <!-- INÍCIO MODAL DE VISUALIZAR MODELO -->
        <modal-component id="modalVisualizar" titulo="Visualizar modelo">
            <template v-slot:conteudo>
                    <inputContainer-component titulo="ID">
                        <input type="text" class="form-control mb-2" :value="$store.state.item.id" disabled>
                    </inputContainer-component>

                    <inputContainer-component titulo="Nome">
                        <input type="text" class="form-control mb-2" :value="$store.state.item.nome" disabled>
                    </inputContainer-component>

                    <inputContainer-component>
                        <img :src="'storage/'+$store.state.item.imagem" v-if="$store.state.item.imagem">
                    </inputContainer-component>

                    <inputContainer-component titulo="Quantidade de portas">
                        <input type="text" class="form-control mb-2" :value="$store.state.item.numero_portas" disabled>
                    </inputContainer-component>

                    <inputContainer-component titulo="Lugares">
                        <input type="text" class="form-control mb-2" :value="$store.state.item.lugares" disabled>
                    </inputContainer-component>

                    <inputContainer-component titulo="Air bag">
                        <input type="text" class="form-control mb-2" :value="$store.state.item.air_bag" disabled>
                    </inputContainer-component>

                    <inputContainer-component titulo="ABS">
                        <input type="text" class="form-control mb-2" :value="$store.state.item.abs" disabled>
                    </inputContainer-component>

                    <inputContainer-component titulo="Data de cadastro">
                        <input type="text" class="form-control mb-2" :value="$store.state.item.created_at | formataData" disabled>
                    </inputContainer-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </template>
        </modal-component>
        <!-- FIM MODAL DE VISUALIZAR MODELO -->

        <!-- INÍCIO MODAL DE REMOVER MODELO -->
        <modal-component id="modalRemover" titulo="Remover modelo">
            <template v-slot:alertas>
                <alert-component v-if="$store.state.transacao.status == 'sucesso'" tipo="success" :detalhes="$store.state.transacao" titulo="Registro removido com sucesso"></alert-component>
                <alert-component v-if="$store.state.transacao.status == 'erro'" tipo="danger"  :detalhes="$store.state.transacao" titulo="Erro ao remover registro"></alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                    <inputContainer-component titulo="ID">
                        <input type="text" class="form-control mb-2" :value="$store.state.item.id" disabled>
                    </inputContainer-component>

                    <inputContainer-component titulo="Carro">
                        <input type="text" class="form-control mb-2" :value="$store.state.item.nome" disabled>
                    </inputContainer-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-danger" @click="remover()" v-if="$store.state.transacao.status != 'sucesso'">Excluir</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </template>
        </modal-component>
        <!-- FIM MODAL DE REMOVER MODELO -->

        <!-- INICIO MODAL DE ATUALIZAR MODELO -->
        <modal-component id="modalEditar" titulo="Atualizar modelo">
            <template v-slot:alertas>
                <alert-component v-if="$store.state.transacao.status == 'sucesso'" tipo="success" :detalhes="$store.state.transacao" titulo="Registro atualizado com sucesso"></alert-component>
                <alert-component v-if="$store.state.transacao.status == 'erro'" tipo="danger"  :detalhes="$store.state.transacao" titulo="Erro ao atualizar registro"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="form-group">
                    <inputContainer-component id="marca_id" titulo="Marca">
                        <input type="text" class="form-control mb-2" id="marca_id" placeholder="ID da marca" :value="$store.state.item.marca_id">
                    </inputContainer-component>
                </div>
                <div class="form-group">
                    <inputContainer-component id="nome" titulo="Nome">
                        <input type="text" class="form-control mb-2" id="nome" placeholder="Nome do modelo" v-model="$store.state.item.nome">
                    </inputContainer-component>
                </div>
                <div class="form-group">
                    <inputContainer-component id="imagem" titulo="Imagem">
                        <input type="file" class="form-control-file mt-2" id="imagem" @change="carregarImagem($event)">
                    </inputContainer-component>
                </div>
                <div class="form-group">
                    <inputContainer-component id="numero_portas" titulo="Qtd portas">
                        <input type="text" class="form-control mb-2" id="numero_portas" placeholder="Quantidade de portas" v-model="$store.state.item.numero_portas">
                    </inputContainer-component>
                </div>
                <div class="form-group">
                    <inputContainer-component id="lugares" titulo="Lugares">
                        <input type="text" class="form-control mb-2" id="lugares" placeholder="Lugares" v-model="$store.state.item.lugares">
                    </inputContainer-component>
                </div>
                <div class="form-group">
                    <inputContainer-component id="air_bag" titulo="Air bag">
                        <select class="form-select" name="air_bag" id="air_bag" v-if="modelos.data != ''">
                            <option :value="$store.state.item.air_bag">{{ $store.state.item.air_bag }}</option>
                            <option value="Sim">Sim</option>
                            <option value="Não">Não</option>
                        </select>
                    </inputContainer-component>
                </div>
                <div class="form-group">
                    <inputContainer-component id="abs" titulo="Abs">
                        <select class="form-select" name="abs" id="abs" v-if="modelos.data != ''">
                            <option :value="$store.state.item.abs">{{ $store.state.item.abs }}</option>
                            <option value="Sim">Sim</option>
                            <option value="Não">Não</option>
                        </select>
                    </inputContainer-component>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
            </template>
        </modal-component>
        <!-- FIM MODAL DE ATUALIZAR MODELO -->        
    </div>
</template>

<script>
    import Paginate from './Paginate.vue'
    export default{
        components: { Paginate },
        data(){
            return {
                urlBase: 'http://localhost:8000/api/v1/modelo',
                urlBaseMarcas: 'http://localhost:8000/api/v1/marca',
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
                formData.append('marca_id', this.$store.state.item.marca_id)
                formData.append('nome', this.$store.state.item.nome)

                if(this.arquivoImagem[0]){
                    formData.append('imagem', this.arquivoImagem[0])
                }

                formData.append('numero_portas', this.$store.state.item.numero_portas)
                formData.append('lugares', this.$store.state.item.lugares)
                formData.append('air_bag', this.$store.state.item.air_bag)
                formData.append('abs', this.$store.state.item.abs)

                axios.post(url,formData,config)
                     .then(response => {

                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = response.data.msg
                        this.carregarLista()

                     })
                     .catch(errors => {

                        this.$store.state.transacao.status = 'erro'
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
                    this.urlBase = l.url.split('?')[1]
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
                let urlMarcas = this.urlBaseMarcas + '?' + this.urlPage + this.urlFiltro

                axios.get(url)
                .then(response => {
                    this.modelos = response.data
                    console.log(this.modelos)

                    axios.get(urlMarcas)
                        .then(response => {
                            this.marcas = response.data
                        })
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
                    this.$store.state.transacao.dados = errors.response.data.errors
                    
                })
            }
        },
        mounted(){
            this.carregarLista()
        }
    }
</script>

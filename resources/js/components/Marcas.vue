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
                                    <input type="number" class="form-control" id="inputID" aria-describedby="helpID" placeholder="ID">
                                </inputContainer-component>
                            </div>
                            <div class="col mb-3">
                                <inputContainer-component id="inputNome" titulo="Nome" help-id="helpNome" texto-ajuda="Informe o Nome (Opcional).">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="helpNome" placeholder="Nome da marca">
                                </inputContainer-component>
                            </div> 
                        </div>  
                    </template>
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right">Pesquisar</button>
                    </template>
                </card-component>
                <!-- FIM DA BUSCA DE MARCAS -->
                <!-- INICIO DA LISTAGEM DE MARCAS -->
                <card-component titulo="Listagem de marcas">
                    <template v-slot:conteudo>
                        <table-component 
                            :dados="marcas.data" 
                            :titulos="{
                                id: { titulo: 'Id', tipo: 'text'},
                                nome: { titulo: 'Nome', tipo: 'text'},
                                imagem: { titulo: 'Imagem', tipo: 'imagem'},
                                created_at: { titulo: 'Data de cadastro', tipo: 'data'},
                            }">
                        </table-component>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-primary btn-sm float-right" data-bs-toggle="modal" data-bs-target="#modalMarcas">Adicionar</button>
                    </template>
                </card-component>
                <!-- FIM DO CARD DE LISTAGEM DE MARCAS -->
            </div>
        </div>
        <!-- MODAL -->
        <modal-component id="modalMarcas" titulo="Adicionar marca">
            <template v-slot:alertas>
                <alert-component tipo="success" v-if="transacaoStatus == 'adicionado'" :detalhes="transacaoDetalhes" titulo="Marca adicionada com sucesso"></alert-component>
                <alert-component tipo="danger"  v-if="transacaoStatus == 'erro'" :detalhes="transacaoDetalhes" titulo="Erro ao adicionar"></alert-component>
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
    </div>
</template>

<script>
    export default{
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
                urlBase: 'http://localhost:8000/api/v1/marca',
                nomeMarca: '',
                arquivoImagem: [],
                transacaoStatus: '',
                transacaoDetalhes: [],
                marcas: { data:[] }
            }
        },
        methods: {
            carregarLista(){
                axios.get(this.urlBase)
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
                        mensagem: 'ID da marca:' + response.data.id
                    }
                    console.log(response)
                })
                .catch(errors => {
                    this.transacaoStatus = 'erro'
                    this.transacaoDetalhes = {
                        mensagem: errors.response.data.message,
                        dados: errors.response.data.errors
                    }
                    console.log(errors)
                })
            }
        },
        mounted(){
            this.carregarLista()
        }
    }
</script>

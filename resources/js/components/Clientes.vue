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
                                    <input type="number" class="form-control" id="inputCPF" aria-describedby="helpCPF" placeholder="CPF do cliente" v-model="buscar.cpf">
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
                        <alert-component v-if="$store.state.transacao.status == 'sucesso'" tipo="success" :detalhes="$store.state.transacao" titulo="Registro adicionado com sucesso"></alert-component>
                        <alert-component v-if="$store.state.transacao.status == 'erro'" tipo="danger"  :detalhes="$store.state.transacao" titulo="Erro ao adicionar registro"></alert-component>
                    </template>
                    <template v-slot:conteudo>
                        <div class="form-group">
                            <inputContainer-component id="placa" titulo="placa">
                                <label for="placa" class="form-label">Placa</label>
                                <input type="text" class="form-control mb-2" id="placa" placeholder="Placa do cliente" v-model="placa">
                            </inputContainer-component>
                        </div>
                        <div class="form-group">
                            <inputContainer-component id="modelo_id" titulo="Modelo ID">
                                <input type="number" min="1" class="form-control mb-2" id="modelo_id" placeholder="Modelo do cliente" v-model="modelo_id">
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
                <!-- FIM MODAL DE ADICIONAR CLIENTE -->

                <!-- INÍCIO MODAL DE VISUALIZAR CLIENTE -->
                <modal-component id="modalVisualizar" titulo="Visualizar cliente">
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

                            <inputContainer-component titulo="Carro">
                                <input type="text" class="form-control mb-2" :value="$store.state.item.placa" disabled>
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
                            <inputContainer-component id="atualizarPlaca">
                                <input type="text" class="form-control mb-2" id="atualizarPlaca" placeholder="Placa do cliente" v-model="$store.state.item.placa">
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
                <!-- FIM MODAL DE ATUALIZAR CLIENTE -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

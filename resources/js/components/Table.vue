<template>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col" v-for="t, key in titulos" :key="key">{{ t.titulo }}</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <!-- Dados de carros -->
            <tr v-for="c in dadosCarros" :key="c.id">
                <th scope="row">{{ c.id }}</th>
                <td>{{ c.placa }}</td>
                <td>{{ c.disponivel == 1 ? 'Sim' : 'Não' }}</td>
                <td>{{ c.created_at | formataData }}</td>
                <td>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalVisualizar" @click="getId(c)">Ver</button>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar" @click="getId(c)">Editar</button>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalRemover" @click="getId(c)">Excluir</button>
                </td>
            </tr>
            <!-- Dados de marcas -->
            <tr v-for="m in dadosMarcas" :key="m.id">
                <th scope="row">{{ m.id }}</th>
                <td>{{ m.nome }}</td>
                <td><img :src="'/storage/' + m.imagem" width="30" height="30"></td>
                <td>{{ m.created_at | formataData }}</td>
                <td>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalVisualizar" @click="getId(m)">Ver</button>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar" @click="getId(m)">Editar</button>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalRemover" @click="getId(m)">Excluir</button>
                </td>
            </tr>
            <!-- Dados de modelos -->
            <tr v-for="d in dadosModelos" :key="d.id">
                <th scope="row">{{ d.id }}</th>
                <td>{{ d.nome }}</td>
                <td><img :src="'/storage/' + d.imagem" width="40" height="30"></td>
                <td>{{ d.numero_portas }}</td>
                <td>{{ d.air_bag == 1 ? 'Sim' : 'Não' }}</td>
                <td>{{ d.created_at | formataData }}</td>
                <td>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalVisualizar" @click="getId(d)">Ver</button>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar" @click="getId(d)">Editar</button>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalRemover" @click="getId(d)">Excluir</button>
                </td>
            </tr>
            <!-- Dados de clientes -->
            <tr v-for="ct in dadosClientes" :key="ct.id">
                <th scope="row">{{ ct.id }}</th>
                <td>{{ ct.nome }}</td>
                <td>{{ ct.cpf }}</td>
                <td>{{ ct.created_at | formataData }}</td>
                <td>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalVisualizar" @click="getId(ct)">Ver</button>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar" @click="getId(ct)">Editar</button>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalRemover" @click="getId(ct)">Excluir</button>
                </td>
            </tr>
            <!-- Dados de locações -->
            <tr v-for="lc in dadosLocacoes" :key="lc.id">
                <th scope="row">{{ lc.id }}</th>
                <td>{{ lc.valor_diaria | replaceValor }}</td>
                <td>{{ lc.km_inicial }}km</td>
                <td>{{ lc.km_final }}km</td>
                <td>{{ lc.created_at | formataData }}</td>
                <td>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalVisualizar" @click="getId(lc)">Ver</button>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar" @click="getId(lc)">Editar</button>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalRemover" @click="getId(lc)">Excluir</button>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: [
            'dadosCarros', 
            'dadosMarcas', 
            'dadosModelos', 
            'dadosClientes',
            'dadosLocacoes',
            'titulos'
        ],
        methods: {
            getId(id){
                this.$store.state.transacao.status = ''
                this.$store.state.transacao.mensagem = ''
                this.$store.state.item = id
            }
        }
    }
</script>

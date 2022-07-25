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
                <td>{{ c.created_at.substring(0, 10) }}</td>
                <td>
                    <button class="btn btn-success">Ver</button>
                    <button class="btn btn-primary">Editar</button>
                    <button class="btn btn-danger">Excluir</button>
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
                <td>{{ d.created_at.substring(0, 10) }}</td>
                <td>
                    <button class="btn btn-success">Ver</button>
                    <button class="btn btn-primary">Editar</button>
                    <button class="btn btn-danger">Excluir</button>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: ['dadosCarros', 'dadosMarcas', 'dadosModelos', 'titulos'],
        methods: {
            getId(id){
                this.$store.state.transacao.status = ''
                this.$store.state.transacao.mensagem = ''
                this.$store.state.item = id
            }
        }
    }
</script>

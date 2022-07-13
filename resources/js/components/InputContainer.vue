<template>
    <div class="form-group">
        <label :for="id" class="form-label">{{ titulo }}</label>
        <slot></slot>
        <div :id="helpID" class="form-text text-muted">{{ textoAjuda }}</div>
    </div>
</template>

<script>
    export default{
        props: ['id','titulo', 'helpID', 'textoAjuda']
    }
</script>

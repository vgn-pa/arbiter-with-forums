<template>
    <div class="preloader-wrapper" v-if="loading">
        <img src="/loading.gif" class="preloader"/>
    </div>
</template>

<script>
    export default {
        props: ['loading']
    }
</script>

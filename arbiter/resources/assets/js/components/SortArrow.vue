<template>
    <span>
        <i class="fa" v-bind:class="{'fa-caret-down': sort.substring(0, 1) == '-', 'fa-caret-up': sort.substring(0, 1) == '+'}"></i>
    </span>
</template>

<script>
    export default {
        props: ['sort']
    }
</script>

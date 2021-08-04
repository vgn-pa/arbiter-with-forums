<template>
    <button v-if="member.phone" v-on:click="call(member.id); clicked = true" :disabled="clicked" class="btn btn-sm" v-bind:class="{ 'btn-success': !clicked, 'btn-danger': clicked }"><i class="fa fa-phone"></i></button>
</template>

<script>
    export default {
        methods: {
            call: function(id) {
                axios.get('api/v1/members/'+id+'/call')
                .then((response) => {
                    this.clicked = false;
                });
            }
        },
        props: ['member'],
        data: function () {
            return {
                clicked: false
            };
        }
    }
</script>

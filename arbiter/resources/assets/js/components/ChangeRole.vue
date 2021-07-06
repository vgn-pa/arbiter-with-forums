<template>
    <div>
        <span v-if="settings.role != 'Admin'">{{ member.role.name }}</span>
        <select v-model="member.role_id" v-on:change="changeRole" v-if="settings.role == 'Admin'">
            <option v-for="option in roles" v-bind:value="option.id">
                {{ option.name }}
            </option>
        </select>
    </div>
</template>

<script>
    export default {
        props: ['member', 'roles', 'settings'],
        methods: {
            changeRole: function() {
                axios.get('/api/v1/members/'+this.member.id+'/role/'+this.member.role_id)
                .then((response) => {
                    this.$notify({
                      group: 'foo',
                      title: 'Success',
                      text: 'User role updated',
                      type: 'success'
                    });
                });
            }  
        },
    }
</script>

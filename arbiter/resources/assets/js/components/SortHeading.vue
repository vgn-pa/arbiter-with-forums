<template>
    <span class="table-sort" v-on:click="changeSort(field)">{{ text }}<sortarrow-component :sort.sync="sort" v-if="sort.substring(1) == field"></sortarrow-component></span>
</template>

<script>
    export default {
        props: ['field', 'text', 'sort'],
        data: function () {
            return {};
        },
        methods: {
            changeSort: function(sort) {
                var thisSort = this.sort.replace('+', '');
                var thisSort = this.sort.replace('-', '');
                // If we're sorting by the same column as the current sort, change direction
                if(thisSort == sort) {
                    if(this.sort.substring(0, 1) == '-') {
                        this.$parent.sort = this.sort.replace('-', '+');
                        return;
                    }
                    if(this.sort.substring(0, 1) == '+') {
                       this.$parent.sort = this.sort.replace('+', '-');
                       return;
                    }
                }
                this.$parent.sort = '-' + sort;
            },
        },
    }
</script>

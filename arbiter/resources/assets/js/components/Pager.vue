<template>
    <div v-if="pager.total > pager.per_page" class="pager text-center">
        <div class="float-left">
            <i class="fa fa-step-backward clickable" v-on:click="start" title="first page" v-bind:class="{ faded: pager.current_page == 1 }"></i>
            <i class="fa fa-chevron-left clickable" v-on:click="prev" title="previous" v-bind:class="{ faded: !pager.prev_page_url }"></i>
        </div>
        {{ pager.from }}-{{ pager.to}} of {{ pager.total }}
        <div class="float-right">
            <i class="fa fa-chevron-right clickable" v-on:click="next" title="next" v-bind:class="{ faded: !pager.next_page_url }"></i>
            <i class="fa fa-step-forward clickable" v-on:click="end" title="last page" v-bind:class="{ faded: pager.current_page == pager.last_page }"></i>
        </div>
        <div class="clear"></div>
    </div>
</template>

<script>
    export default {
        props: ['pager'],
        methods: {
            next: function() {
                if(!this.pager.next_page_url) return;
                this.$parent.loading = true;
                this.fetch(this.pager.next_page_url);
            },
            prev: function() {
                if(!this.pager.prev_page_url) return;
                this.$parent.loading = true;
                this.fetch(this.pager.prev_page_url);
            },
            end: function() {
                if(this.pager.current_page == this.pager.last_page) return;
                this.$parent.loading = true;
                this.fetch(this.pager.last_page_url);
            },
            start: function() {
                if(this.pager.current_page == 1) return;
                this.$parent.loading = true;
                this.fetch(this.pager.first_page_url);
            },
            fetch: function(url) {
                axios.get(url).then((response) => {
                    this.$parent.data = response.data.data;
                    this.$parent.pager = response.data;
                    this.$parent.loading = false;
                });
            }
        }
    }
</script>

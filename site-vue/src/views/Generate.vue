<template>
    <div class="home">
        <b-button variant="danger" class="vue-button" v-on:click="generate">Generate test data</b-button>
    </div>
</template>
<script>
    import axios from 'axios'
    export default {
        name: 'Generate',
        data() {
            return {
                loading: true,
                errored: false,
                endpoint: 'http://127.0.0.1:8085/'
            }
        },
        methods: {
            setMessage: function (event) {
                this.msg = event.target.value;
            },
            generate() {
                axios.post(this.endpoint+'generate')
                    .then(response => {
                        this.$router.push({
                            path:'/'
                        });
                    })
                    .catch(error => {
                        console.log('-----error-------');
                        console.log(error);
                        this.errored = true;
                    })
                    .finally( ()=> {
                        this.loading = false;
                    })
            },
        },
    }
</script>

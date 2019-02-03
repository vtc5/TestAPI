<template>
    <div class="home">
        <LoadingIndicator v-bind:loading="loading"></LoadingIndicator>
        <b-button variant="danger" class="vue-button" v-on:click="generate">Generate test data</b-button>
    </div>
</template>
<script>
    import axios from 'axios';
    import LoadingIndicator from "../components/loadingIndicator";
    export default {
        name: 'Generate',
        components: {LoadingIndicator},
        data: function () {
            return {
                loading: false,
                errored: false,
            }
        },
        methods: {
            setMessage: function (event) {
                this.msg = event.target.value;
            },
            generate() {
                this.loading = true;
                axios.post(this.$getServerAddress()+'generate', null,
                    {
                        params: {
                            key: this.$getAuthKey(),
                        }
                    }
                )
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

<template>
    <div class="login">
        <div v-esc="escape"></div>
        <LoadingIndicator v-bind:loading="loading"></LoadingIndicator>
        <h1>Login</h1>
        <section v-if="loading">
        </section>
        <section v-else>
            <section v-if="errored">
                <ShowErrors v-bind:errors="errors"></ShowErrors>
            </section>
            <section v-else>
                <form v-on:submit="userLogin">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="userName">Name</label>
                            <input id="userName" class="form-control" type="text" v-model="userName">
                        </div>
                        <div class="form-group">
                            <label for="userPassword">Password</label>
                            <input id="userPassword" class="form-control" type="text" v-model="userPassword">
                        </div>
                        <b-button-group class="col-md-4 pb-2">
                            <b-button type="submit" variant="primary" class="vue-button">Login</b-button>
                            <b-button variant="primary" class="vue-button" v-on:click="userClose()">Cancel</b-button>
                        </b-button-group>
                    </div>
                </form>
            </section>
        </section>
    </div>
</template>

<script>
    import axios from 'axios'
    import ShowErrors from '@/components/showErrors'
    import LoadingIndicator from "../components/loadingIndicator";
    export default {
        name: "Login",
        components: {
            LoadingIndicator,
            ShowErrors
        },
        data() {
            return {
                loading: false,
                errored: false,
                userName: '',
                userPassword: '',
                errors: []
            }
        },
        methods: {
            userLogin() {
                this.loading = true;
                axios.post(this.$getServerAddress()+'login',
                    JSON.stringify({
                        username:this.userName,
                        password:this.userPassword,
                    })
                ).then(response =>{
                    if (response.data.errors.length == 0) {
                        this.$setAuthPatameters(response.data.sessionId);
                        localStorage.setItem('sessionId',response.data.sessionId);
                        this.$router.push({
                            path:'/'
                        });
                    }
                }).catch(error => {
                    this.$setAuthPatameters(null);
                    this.errors = [
                        {
                            code:error.code,
                            message: error.message
                        }
                    ];
                    this.errored = true;
                }).finally(()=>{
                    this.loading = false;
                })
                ;
            },
            escape(event) {
                this.userClose();
            },
            userClose() {
                this.$router.push({
                    path:'/'
                });
            },
        }
    }
</script>

<style scoped>

</style>
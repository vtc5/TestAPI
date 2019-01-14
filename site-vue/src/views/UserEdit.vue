<template>
    <div class="company">
        <div v-esc="escape"></div>
        <LoadingIndicator v-bind:loading="loading"></LoadingIndicator>
        <section v-if="isEditUser">
            <h1>Edit user</h1>
            <section v-if="loading">
            </section>
            <section v-else>
                <section v-if="errored">
                    <ShowErrors v-bind:errors="errors"></ShowErrors>
                </section>
                <section v-else>
                    <form v-on:submit="userSave">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="userName">Name</label>
                                <input id="userName" class="form-control" type="text" v-model="userName">
                            </div>
                            <div class="form-group">
                                <label for="userEMail">E-Mail</label>
                                <input id="userEMail" class="form-control" type="email" v-model="userEMail">
                            </div>
                            <div class="form-group">
                                <label for="userCompany">Company</label>
                                <v-select id="userCompany" :options="userCompanies" v-model="companySelected" @search="onSearch"></v-select>
                            </div>
                            <b-button-group class="col-md-4 pb-2">
                                <b-button type="submit" variant="primary" class="vue-button">Save</b-button>
                                <b-button variant="primary" class="vue-button" v-on:click="userClose()">Cancel</b-button>
                            </b-button-group>
                        </div>
                    </form>
                </section>
            </section>
        </section>
        <section v-else>
            <h1>Delete user</h1>
            <section v-if="loading">
                Loading....
            </section>
            <section v-else>
                <section v-if="errored">
                    <ShowErrors v-bind:errors="errors"></ShowErrors>
                    <b-button variant="primary" class="vue-button" v-on:click="userClose()">Cancel</b-button>
                </section>
                <section v-else>
                    <div class="modal-body">
                        <div class="form-group">
                            Name: {{ userName }}
                        </div>
                        <div class="form-group">
                            E-Mail: {{ userEMail }}TB
                        </div>
                        <div class="form-group">
                            Company: {{ userCompanyName }}TB
                        </div>
                        <b-button-group class="col-md-4 pb-2">
                            <b-button variant="danger" class="vue-button" v-on:click="userDelete()">Delete</b-button>
                            <b-button variant="primary" class="vue-button" v-on:click="userClose()">Cancel</b-button>
                        </b-button-group>
                    </div>
                </section>
            </section>
        </section>
    </div>
</template>
<style>
    .vue-button {
        margin-left: 10px;
        margin-right: 10px;
    }
</style>
<script>
    import axios from 'axios'
    import { debounce } from "debounce";
    import ShowErrors from '@/components/showErrors'
    import LoadingIndicator from "../components/loadingIndicator";

    export default {
        name: 'UserEdit',
        components: {
            LoadingIndicator,
            ShowErrors
        },
        data() {
            return {
                id: null,
                isEditUser: true,
                loading: true,
                errored: false,
                endpoint: 'http://127.0.0.1:8085/',
                userId: null,
                userName: '',
                userEMail: '',
                userCompanyName: '',
                userCompanyId: 0,
                userCompanies: [],
                companySelected: {
                    value: null,
                    label: null
                },
                errors: []
            }
        },
        created() {
            if (this.$route.params.isEdit == 'delete') {
                this.isEditUser = false;
            }
            this.getUser(this.$route.params.id);
        },
        methods: {
            getUser(id=null) {
                if (id == null) {
                    this.errored = true;
                } else {
                    axios.get(this.endpoint+'users/'+id)
                        .then(response => {
                            this.userId = response.data.id;
                            this.userName = response.data.name;
                            this.userEMail = response.data.email;
                            this.userCompanyId = response.data.companyid;
                            this.userCompanyName = response.data.companyName;
                            this.companySelected = {
                                value:response.data.companyid,
                                label:response.data.companyName
                            };
                            if (this.userId == null) {
                                this.errored = true;
                            }
                        })
                        .catch(error => {
                            this.errors = [
                                {
                                    code:error.code,
                                    message: error.message
                                }
                            ];
                            this.errored = true;
                        })
                        .finally( ()=> {
                            this.loading = false;
                        })
                }
            },
            userSave() {
                axios.put(this.endpoint+'users/'+this.userId,
                    JSON.stringify({
                            Name:this.userName,
                            EMail:this.userEMail,
                            company_id: this.companySelected.value
                        })
                ).then(response =>{
                    if (response.data.errors.length == 0) {
                        this.$router.push({
                            path:'/users'
                        });
                    }
                })
                .catch(error => {
                    this.errors = [
                        {
                            code:error.code,
                            message: error.message
                        }
                    ];
                    this.errored = true;
                })
                ;
            },
            userDelete() {
                axios.delete(
                    this.endpoint+'users/'+this.companyId, null
                ).then(response =>{
                    if (response.data.errors.length == 0) {
                        this.$router.push({
                            path:'/users'
                        });
                    } else {
                        this.errors = response.data.errors;
                        this.errored = true;
                    }
                }).catch(error => {
                    this.errors = [
                        {
                            code:error.code,
                            message: error.message
                        }
                    ];
                    this.errored = true;
                });
            },
            escape(event) {
                this.userClose();
            },
            userClose() {
                this.$router.push({
                    path:'/users'
                });
            },
            onSearch(search, loading) {
                if (search.length > 2) {
                    this.searchElement(this.endpoint, loading, search, this);
                }
            },
            searchElement: debounce((endpoint, loading, search, vm) => {
                loading(true);
                axios.get(
                    endpoint + 'companies/select_vue',
                    {
                        params: {
                            term:search,
                            maxRows:100,
                            page:1
                        }
                    }
                ).then(response =>{
                    vm.userCompanies = response.data.results;
                    loading(false);
                }).catch(error => {
                    loading(false);
                })
            },350)
        }
    }
</script>

<template>
    <div class="company">
        <div v-esc="escape"></div>
        <section v-if="isEditCompany">
            <h1>Edit company</h1>
            <section v-if="loading">
                Loading....
            </section>
            <section v-else>
                <section v-if="errored">
                    <ShowErrors v-bind:errors="errors"></ShowErrors>
                </section>
                <section v-else>
                    <form v-on:submit="companySave">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="companyName">Name</label>
                                <input id="companyName" class="form-control" type="text" v-model="companyName">
                            </div>
                            <div class="form-group">
                                <label for="companyLimit">Limit</label>
                                <input id="companyLimit" class="form-control" type="number" v-model="companyLimit">
                            </div>
                            <b-button-group class="col-md-4 pb-2">
                                <b-button type="submit" variant="primary" class="vue-button">Save</b-button>
                                <b-button variant="primary" class="vue-button" v-on:click="companyClose()">Cancel</b-button>
                            </b-button-group>
                        </div>
                    </form>
                </section>
            </section>
        </section>
        <section v-else>
            <h1>Delete company</h1>
            <section v-if="loading">
                Loading....
            </section>
            <section v-else>
                <section v-if="errored">
                    <ShowErrors v-bind:errors="errors"></ShowErrors>
                    <b-button variant="primary" class="vue-button" v-on:click="companyClose()">Cancel</b-button>
                </section>
                <section v-else>
                    <div class="modal-body">
                        <div class="form-group">
                            Name: {{ companyName }}
                        </div>
                        <div class="form-group">
                            Limit: {{ companyLimit }}TB
                        </div>
                        <b-button-group class="col-md-4 pb-2">
                            <b-button variant="danger" class="vue-button" v-on:click="companyDelete()">Delete</b-button>
                            <b-button variant="primary" class="vue-button" v-on:click="companyClose()">Cancel</b-button>
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
    import ShowErrors from '@/components/showErrors'

    export default {
        name: 'CompanyEdit',
        components: {
            ShowErrors
        },
        data() {
            return {
                id: null,
                isEditCompany: true,
                loading: true,
                errored: false,
                endpoint: 'http://127.0.0.1:8085/',
                companyId: null,
                companyName: '',
                companyLimit: 0,
                errors: []
            }
        },
        created() {
            if (this.$route.params.isEdit == 'delete') {
                this.isEditCompany = false;
            }
            this.getCompany(this.$route.params.id);
        },
        methods: {
            getCompany(id=null) {
                if (id == null) {
                    this.errored = true;
                    this.message = 'User doesn\'t exit';
                } else {
                    axios.get(this.endpoint+'company/'+id)
                        .then(response => {
                            this.companyId = response.data.id;
                            this.companyName = response.data.name;
                            this.companyLimit = response.data.quota;
                            if (this.companyId == null) {
                                this.errored = true;
                                //this.message = 'User doesn\'t exit';
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
            companySave() {
                axios.put(this.endpoint+'companies/'+this.companyId,
                    JSON.stringify({
                            Name:this.companyName,
                            Quota:this.companyLimit
                        })
                ).then(response =>{
                    if (response.data.errors.length == 0) {
                        this.$router.push({
                            path:'/companies'
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
            companyDelete() {
                axios.delete(
                    this.endpoint+'companies/'+this.companyId, null
                ).then(response =>{
                    if (response.data.errors.length == 0) {
                        this.$router.push({
                            path:'/companies'
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
              this.companyClose();
            },
            companyClose() {
                this.$router.push({
                    path:'/companies'
                });
            }
        }
    }
</script>

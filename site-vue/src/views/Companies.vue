<template>
    <div class="companies">
        <!--<input type="text" v-on:input="setMessage" />-->
        <!--<h1>This is an page3 </h1>-->
        <!--<h1>{{  msg }} </h1>-->
        <LoadingIndicator v-bind:loading="loading"></LoadingIndicator>
        <h1>Companies</h1>
        <section v-if="errored">
            <p>We're sorry, we're not able to retrieve this information at the moment, please try back later</p>
        </section>
        <section v-else>
            <div v-if="loading"></div>
            <table class="table" v-else>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Limit</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="company in companies">
                    <td>{{ company.name }}</td>
                    <td>{{ company.quota | currencydecimal }}ТВ</td>
                    <td>
                        <button v-on:click="editCompany(company)">Edit</button>
                        <button v-on:click="deleteCompany(company)">Delete</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </section>
    </div>
</template>

<script>
    import axios from 'axios'
    import loadingImage from '../assets/ajax-loader.gif';
    import LoadingIndicator from "../components/loadingIndicator";
    export default {
        name: 'Companies',
        components: {LoadingIndicator},
        mounted() {
            this.$socket.onmessage = (data)=>{
                console.log(data.data);
            };
        },
        data() {
            return {
                companies: null,
                loading: true,
                errored: false,
                IndicatorMessage:"Loading...",
                image:loadingImage
            }
        },
        created() {
            this.getAllCompanies();
            this.$connect();
        },
        methods: {
            setMessage: function (event) {
                this.msg = event.target.value;
            },
            getAllCompanies() {
                axios.get(this.$getServerAddress()+'companies',
                    {
                        params: {
                            key: this.$getAuthKey(),
                        }
                    }
                )
                    .then(response => {
                        this.companies = response.data;
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
            editCompany(company) {
                this.$router.push({
                    path:'/company/'+company.id+'/edit'
                });
            },
            deleteCompany(company) {
                this.$router.push({
                    path:'/company/'+company.id+'/delete'
                });
            }
        },
        filters: {
            currencydecimal (value) {
                return value.toFixed(2)
            }
        }
    }
</script>

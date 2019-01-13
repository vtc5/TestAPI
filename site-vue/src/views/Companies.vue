<template>
    <div class="companies">
        <!--<input type="text" v-on:input="setMessage" />-->
        <!--<h1>This is an page3 </h1>-->
        <!--<h1>{{  msg }} </h1>-->
        <h1>Companies</h1>
        <section v-if="errored">
            <p>We're sorry, we're not able to retrieve this information at the moment, please try back later</p>
        </section>
        <section v-else>
            <div v-if="loading">Loading...</div>
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
    export default {
        name: 'Companies',
        data() {
            return {
                companies: null,
                loading: true,
                errored: false,
                endpoint: 'http://127.0.0.1:8085/'
            }
        },
        created() {
            this.getAllCompanies();
        },
        methods: {
            setMessage: function (event) {
                this.msg = event.target.value;
            },
            getAllCompanies() {
                axios.get(this.endpoint+'companies')
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

<template>
    <div class="users">
        <h1>Users</h1>
        <section v-if="errored">
            <p>We're sorry, we're not able to retrieve this information at the moment, please try back later</p>
        </section>
        <section v-else>
            <div v-if="loading">Loading...</div>
            <table class="table" v-else>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>E-Mail</th>
                    <th>Company</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user in users">
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.companyName }}</td>
                    <td>
                        <button v-on:click="editUser(user)">Edit</button>
                        <button v-on:click="deleteUser(user)">Delete</button>
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
        name: 'Users',
        data() {
            return {
                users: null,
                loading: true,
                errored: false,
                endpoint: 'http://127.0.0.1:8085/'
            }
        },
        created() {
            this.getAllUsers();
        },
        methods: {
            setMessage: function (event) {
                this.msg = event.target.value;
            },
            getAllUsers() {
                axios.get(this.endpoint+'users')
                    .then(response => {
                        this.users = response.data;
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
            editUser(user) {
                this.$router.push({
                    path:'/user/'+user.id+'/edit'
                });
            },
            deleteUser(user) {
                this.$router.push({
                    path:'/user/'+user.id+'/delete'
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

<template>
  <div class="home">
    <LoadingIndicator v-bind:loading="loading"></LoadingIndicator>
    <div style="display: inline-block;">
      <label for="reportMonth">Month</label>
      <v-select style="width: 200px; margin-right: 20px" id="reportMonth" v-model="selectedMonth" :options="months"></v-select>
    </div>
    <b-button variant="primary" class="vue-button" v-on:click="getReport">Go</b-button>
    <table class="table"  style="margin-top: 15px;">
      <thead>
      <tr>
        <th>Name</th>
        <th>Used</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="line in reportLines">
        <td>{{ line.name }}</td>
        <td>{{ line.quota }}TB ({{ line.transfered }}TB)</td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
  import axios from 'axios';
  import LoadingIndicator from "../components/loadingIndicator";
  export default {
    name: 'Home',
    components: {LoadingIndicator},
    data: function () {
      return {
        reportLines: [],
        loading: false,
        errored: false,
        months: [
          'January',
          'February',
          'March',
          'April',
          'May',
          'June',
          'July',
          'August',
          'September',
          'October',
          'November',
          'December'
        ],
        selectedMonth: null
      }
    },
    methods: {
      setMessage: function (event) {
        this.msg = event.target.value;
      },
      getReport() {
        if (this.selectedMonth == null) {
          return;
        }
        this.loading = true;
        axios.get(this.$getServerAddress()+"report/"+this.selectedMonth,
                {
                  params: {
                    key: this.$getAuthKey(),
                  }
                }
              ).then(response => {
                  this.reportLines = response.data.report;
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

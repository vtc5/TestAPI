<template>
  <div class="home">
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
  import axios from 'axios'
  export default {
    name: 'Home',
    data() {
      return {
        reportLines: [],
        loading: true,
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
        selectedMonth:null,
        endpoint: 'http://127.0.0.1:8085/'
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
        axios.get(this.endpoint+"report/"+this.selectedMonth)
                .then(response => {
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

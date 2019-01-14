import Vue from 'vue'
import App from './App.vue'
import router from './router'
import BootstrapVue from 'bootstrap-vue'
import vSelect from 'vue-select'
import VueEsc from 'vue-esc';
import BlockUI from 'vue-blockui';


import "bootstrap/dist/css/bootstrap.min.css"

Vue.config.productionTip = false;

Vue.use(BootstrapVue);
Vue.use(VueEsc);
Vue.component('v-select', vSelect);
Vue.use(BlockUI);

new Vue({
  router,
  render: function (h) { return h(App) }
}).$mount('#app')


import Vue from 'vue'
import App from './App.vue'
import router from './router'
import BootstrapVue from 'bootstrap-vue'
import vSelect from 'vue-select'
import VueEsc from 'vue-esc';
import BlockUI from 'vue-blockui';
import Auth from './Plugins/Authenticate.js'
import Vuex from 'vuex';
import VueNativeSock from 'vue-native-websocket';

import "bootstrap/dist/css/bootstrap.min.css"

Vue.config.productionTip = false;

Vue.use(BootstrapVue);
// Vue.use(Vuex);
Vue.use(VueEsc);
Vue.component('v-select', vSelect);
Vue.use(BlockUI);
Vue.use(VueNativeSock, 'ws://localhost:8086?user=123',{
      format: 'json',
      connectManually: true,
      reconnection: true, // (Boolean) whether to reconnect automatically (false)
      reconnectionAttempts: 5, // (Number) number of reconnection attempts before giving up (Infinity),
      reconnectionDelay: 3000, // (Number) how long to initially wait before attempting a new (1000)
    });
Vue.use(Auth,{
    protocol:'http',
    server:'127.0.0.1',
    port:'8085'
});

new Vue({
  router,
  render: function (h) { return h(App) }
}).$mount('#app');


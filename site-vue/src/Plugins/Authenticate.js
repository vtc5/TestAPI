import axios from 'axios';

const Auth = {
    install: function (Vue, options) {
        Vue.serverAddress = options.protocol+'://'
            +options.server+':'+options.port+'/';
        Vue.authChecked = false;
        Vue.authCheckedRunning = false;
        var sessionId = localStorage.getItem('sessionId');
        if (sessionId == null) {
            Vue.authenticated = false;
            Vue.authKey = '';
        } else {
            Vue.authenticated = true;
            Vue.authKey = sessionId;
        }
        Vue.prototype.$getServerAddress = function () {
            return Vue.serverAddress;
        };
        Vue.prototype.$getAuthKey = function () {
            return Vue.authKey;
        };
        Vue.prototype.$setAuthPatameters = function ($sessionId) {
            if ($sessionId == null) {
                Vue.authenticated = false;
                Vue.authKey = '';
            } else  {
                Vue.authChecked = true;
                Vue.authenticated = true;
                Vue.authKey = $sessionId;
            }
        };
        Vue.mixin({
            mounted() {
                if (!Vue.authenticated) {
                    this.$router.push({
                        path:'/login'
                    });
                } else {
                    if (!Vue.authChecked  && !Vue.authCheckedRunning)  {
                        Vue.authCheckedRunning = true;
                        var url = Vue.serverAddress+'login_check?key='+sessionId;
                        var ret = axios.post(url).then(response=>{
                            if (response.status===200) {
                                Vue.authenticated = true;
                                Vue.authChecked = true;
                            } else {
                                Vue.authenticated = false;
                                Vue.authChecked = false;
                                Vue.authKey = '';
                                this.$router.push({
                                    path:'/login'
                                });
                            }
                        }).catch(error=>{
                            Vue.authenticated = false;
                            Vue.authKey = '';
                            Vue.authChecked = false;
                            this.$router.push({
                                path:'/login'
                            });
                        }).finally(()=>{
                            Vue.authCheckedRunning = false;
                            })
                        ;
                    }
                }
            }
        });
    }
}

export default Auth;
import 'core-js/stable'
import Vue from 'vue'
import App from './App'
import router from './router'
import CoreuiVue from '@coreui/vue'
import {
    iconsSet as icons
} from './assets/icons/icons.js'
import store from './store'
import VueSocketIO from 'vue-3-socket.io'
import SocketIO from 'socket.io-client'

const options = {transports: ['websocket', 'polling', 'flashsocket'],secure: true}
const socket = SocketIO('http://127.0.0.1:3000', options);
Vue.use(new VueSocketIO({
    debug: true,
    connection: socket, //options object is Optional
    vuex: {
      store,
      actionPrefix: "SOCKET_",
      mutationPrefix: "SOCKET_"
    }
  })
);
Vue.config.performance = true
Vue.use(CoreuiVue)
new Vue({
    el: '#app',
    router,
    store,
    icons,
    socket,
    template: '<App/>',
    components: {
        App
    },
})

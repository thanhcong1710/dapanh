import 'core-js/stable'
import Vue from 'vue'
import App from './App'
import router from './router'
import CoreuiVue from '@coreui/vue'
import {
    iconsSet as icons
} from './assets/icons/icons.js'
import store from './store'
import VueInternationalization from 'vue-i18n';
import Locale from '../../js/vue-i18n-locales.generated.js';
import VueSocketIO from 'vue-3-socket.io'
import SocketIO from 'socket.io-client'

Vue.use(VueInternationalization);
const i18n = new VueInternationalization({
    locale: document.head.querySelector('meta[name="locale"]').content,
    messages: Locale
});

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
    i18n,
    socket,
    template: '<App/>',
    components: {
        App
    },
})

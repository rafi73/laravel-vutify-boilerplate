require('./bootstrap');

// Vue
import Vue from 'vue'
import App from './components/App'

// Vuetify
import Vuetify from 'vuetify'
import colors from 'vuetify/es5/util/colors'

Vue.use(Vuetify, {
    theme: {
        primary: colors.indigo.base,
        secondary: colors.blue.base,
        accent: colors.amber.base,
    }
});
import 'vuetify/dist/vuetify.min.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'

import Vuex from 'vuex';
Vue.use(Vuex);

import StoreData from './store';
const store = new Vuex.Store(StoreData);
// Vue-Router
import router from './router'

import VeeValidate from 'vee-validate'

Vue.use(VeeValidate, { delay: 250 })

Vue.mixin({
    $_veeValidate: {
        validator: 'new'
    },
    methods: {
        async formHasErrors() {
            const valid = await this.$validator.validateAll()
            if (valid) {
                this.$validator.pause()
            }
            return !valid
        }
    }
})


// Main app
const app = new Vue({
    el: '#app',
    render: h => h(App),
    router,
    store
});
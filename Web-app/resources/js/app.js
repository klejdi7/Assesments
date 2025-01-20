require('./bootstrap');

import Vue from 'vue';
import VueAxios from 'vue-axios';
import VueRouter from 'vue-router';
import axios from 'axios';
import { routes } from './routes';

Vue.use(VueRouter);
Vue.use(VueAxios, axios);


const router = new VueRouter({
    mode: 'history',
    routes: routes
});

Vue.component('comment-section', require('./components/utils/CommentSection.vue').default);
Vue.component('edit-modal', require('./components/utils/EditPostModal.vue').default);
Vue.component('comment-modal', require('./components/utils/CommentModal.vue').default);
Vue.component('login-modal', require('./components/utils/LoginModal.vue').default);
Vue.component('register-modal', require('./components/utils/RegisterModal.vue').default);
Vue.component('post-modal', require('./components/utils/PostModal.vue').default);

var moment = require('moment');

const app = new Vue({
    el: '#app',
    router: router,

    data() {

        return {

			moment: moment,

            // User Data ------
            isLoggedin: false,
			user: [],
            // ----------------
        }

    },
	watch: {
    },
	
	beforeMount() {
        this.getUser();  
    },

	methods: {

        getUser: function() {
                this.axios .get('/data/user').then(response => {
                    this.user = response.data;
                    this.isLoggedin = true;
                })
                .catch(e => {
                    this.isLoggedin = e.response.data.logged_in;
                }); 
        },


    },
});


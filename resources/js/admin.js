require('./bootstrap');

window.Vue = require('vue');

import LoggedInUsersList from './components/admin/LoggedInUsersList'
window.Vue.component('logged-in-users-list', LoggedInUsersList);

import './bootstrap';

import Vue from 'vue';
window.Vue = Vue;

import LoggedInUsersList from './components/admin/LoggedInUsersList'
window.Vue.component('logged-in-users-list', LoggedInUsersList);

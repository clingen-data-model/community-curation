require('./bootstrap');

require('sirs-skiptrigger')
require('mutually-exclusive')

/**
 * require blank scss file to force include of style-loader and css-loader
 */
require('../sass/blank.scss')

window.datepicker = require('bootstrap-datepicker');
window.timepicker = require('timepicker');
window.Vue = require('vue');

import getAllCurationActivities from './resources/curation_activities/get_all_curation_activities'
import getAllCurationGroups from './resources/curation_groups/get_all_curation_groups'

import moment from 'moment-timezone'
window.moment = moment;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

import './register_bootstrapvue_plugins.js'
import './register_components.js';
import './register_filters.js';

import QueryStringFromParams from './http/query_string_from_params'

import store from './store/index'
import { mapActions, mapGetters } from 'vuex'

function evaluate(el, binding, vnode) {
    console.log(binding);
}

window.clearSessionStorage = function() {
    sessionStorage.removeItem('user');
    sessionStorage.removeItem('impersonatable-users');
}



window.axios.interceptors.request.use(function(config) {
    store.commit('addRequest');
    const apiParts = config.url.split(/[\/?&]/)
    return config;
})

window.axios.interceptors.response.use(
    function(response) {
        store.commit('removeRequest');
        return response;
    },
    function(error) {
        store.commit('removeRequest');
        return Promise.reject(error);
    }
);

if (document.getElementById('app')) {
    const app = new window.Vue({
        el: '#app',
        store: store,
        data: {
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
        },
        computed: {
            infoMessages() {
                console.log('getInfoMessages');
                return this.$store.state.messages.info
            },
            loading() {
                return this.$store.getters.loading;
            }
        },
        methods: {
            ...mapActions([
                'fetchUser',
            ]),
            clearSessionStorage() {
                window.clearSessionStorage();
            },
            refreshUser() {
                console.log('refreshUser');
                sessionStorage.removeItem('user');
                this.fetchUser();
            }
        },
        mounted() {
            this.fetchUser();
        }
    });
}


function clearChildren(el) {
    let child = el.lastElementChild;
    while (child) {
        el.removeChild(child);
        child = el.lastElementChild;
    }
}

function createOption({ value, label }) {
    let option = document.createElement('option');
    option.setAttribute('value', value);
    option.innerText = label;
    return option;
}

let curationActivities = [];
async function loadActivities() {
    curationActivities = await getAllCurationActivities();

    Array.from(document.querySelectorAll('select.curation-activity-question'))
        .forEach(question => {
            question.disabled = false;
        })
}

let panels = [];
async function loadPanels() {
    panels = await await getAllCurationGroups();
    Array.from(document.querySelectorAll('select.panel-question'))
        .forEach(question => {
            question.disabled = false;
        })
}

document.addEventListener('DOMContentLoaded', () => {
    const curationActivityQuestions = Array.from(document.querySelectorAll('select.curation-activity-question'));

    loadActivities();

    loadPanels();

    curationActivityQuestions
        .forEach(question => {
            question.addEventListener('change', (evt) => {
                // Update options for curation group selection
                const activityPanels = panels.filter(panel => {
                    return panel.curation_activity_id == question.value &&
                        panel.accepting_volunteers == 1
                });
                const panelQuestion = question.parentElement.parentElement.parentElement.querySelector('select.panel-question');
                const panelQuestionParent = question.parentElement.parentElement.parentElement.querySelector('.panel-question');
                clearChildren(panelQuestion);
                panelQuestion.appendChild(createOption({ value: '', label: 'Select...' }))
                activityPanels.forEach(panel => panelQuestion.appendChild(createOption({ value: panel.id, label: panel.name })))

                if (activityPanels.length == 0 && question.value != '') {
                    const note = document.createElement('small');
                    note.classList.add('not-accepting-note', 'alert', 'alert-warning', 'mt-1');
                    note.innerHTML = `No curation groups in the selected activity are currently accepting volunteers. We'll hold your application until one becomes available.`;
                    panelQuestion.setAttribute('style', { display: 'none' });
                    panelQuestionParent.appendChild(note);
                    panelQuestion.disabled = true;
                } else {
                    panelQuestionParent.querySelector('.not-accepting-note').remove();
                    panelQuestion.setAttribute('style', { display: 'inline-block' });
                    panelQuestion.disabled = false;
                }
            });
        });


    if (document.getElementById('curation_activity_1')) {
        document.getElementById('curation_activity_1')
            .addEventListener('change', evt => {
                const availableActivities = curationActivities.filter(activity => activity.id != evt.target.value)

                const ca2 = document.querySelector('[name=curation_activity_2]')
                clearChildren(ca2);
                ca2.appendChild(createOption({ value: '', label: 'Select...' }))
                availableActivities.forEach(activity => {
                    ca2.appendChild(createOption({
                        value: activity.id,
                        label: activity.name
                    }))
                })

                const ca3 = document.querySelector('[name=curation_activity_3]')
                clearChildren(ca3);
                ca3.appendChild(createOption({ value: '', label: 'Select...' }))
                availableActivities.forEach(activity => ca3.appendChild(createOption({ value: activity.id, label: activity.name })))
            });

        document.getElementById('curation_activity_2')
            .addEventListener('change', evt => {
                const availableActivities = curationActivities.filter(activity => {
                    return activity.id != evt.target.value &&
                        activity.id != document.querySelector('[name=curation_activity_1]').value
                })

                const ca3 = document.querySelector('[name=curation_activity_3]')
                clearChildren(ca3);
                ca3.appendChild(createOption({ value: '', label: 'Select...' }))
                availableActivities.forEach(activity => ca3.appendChild(createOption({ value: activity.id, label: activity.name })))
            });
    }
});
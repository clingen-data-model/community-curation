require('./bootstrap');

require('sirs-skiptrigger')
require('mutually-exclusive')

window.datepicker = require('bootstrap-datepicker');
window.timepicker = require('timepicker');
window.Vue = require('vue');

import getAllCurationActivities from './resources/curation_activities/get_all_curation_activities'
import getAllExpertPanels from './resources/expert_panels/get_all_expert_panels'

import moment from 'moment'

// window.Vue.use(BootstrapVue);
import { BadgePlugin, CardPlugin, CollapsePlugin, DropdownPlugin, ModalPlugin, PaginationPlugin, TabsPlugin, TablePlugin, PopoverPlugin } from 'bootstrap-vue';
window.Vue.use(BadgePlugin);
window.Vue.use(CardPlugin);
window.Vue.use(CollapsePlugin);
window.Vue.use(DropdownPlugin);
window.Vue.use(ModalPlugin);
window.Vue.use(PaginationPlugin);
window.Vue.use(TabsPlugin);
window.Vue.use(TablePlugin);
window.Vue.use(PopoverPlugin);

// localStorage.clear();

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

import VolunteerIndex from './components/volunteers/VolunteerIndex';
import VolunteerDetail from './components/volunteers/VolunteerDetail';
import AssignmentForm from './components/assignments/AssignmentForm';
import NonVolunteer from './components/NonVolunteer';
import OnlyVolunteer from './components/OnlyVolunteer';
import QuestionBlock from './components/forms/QuestionBlock'
import RadioInput from './components/forms/inputs/RadioInput'
import RadioGroup from './components/forms/inputs/RadioGroup'

import AttestationForm from './components/attestations/AttestationForm'
import ActionabilityBasicForm from './components/attestations/forms/ActionabilityBasic'
import DosageBasicForm from './components/attestations/forms/DosageBasic'
import BaselineBasicForm from './components/attestations/forms/BaselineBasic'
import BaselineGeneticForm from './components/attestations/forms/BaselineGenetic'
import GeneBasicForm from './components/attestations/forms/GeneBasic'
import SomaticBasicForm from './components/attestations/forms/SomaticBasic'
import VariantBasicForm from './components/attestations/forms/VariantBasic'

import ValidationError from './components/ValidationError';
import DateField from './components/DateField'
import HypothesisLink from './components/HypothesisLink'
import TrainingSessionList from './components/training_sessions/TrainingSessionList'
import TrainingSessionDetail from './components/training_sessions/TrainingSessionDetail'

import ImpersonateControl from './components/ImpersonateControl'

import Row from './components/layout/Row'
window.Vue.component('row', Row);
import Column from './components/layout/Col'
window.Vue.component('column', Column);

window.Vue.component('impersonate-control', ImpersonateControl);

window.Vue.component('validation-error', ValidationError);

window.Vue.component('volunteer-index', VolunteerIndex);
window.Vue.component('volunteer-detail', VolunteerDetail);
window.Vue.component('assignment-form', AssignmentForm);
window.Vue.component('non-volunteer', NonVolunteer);
window.Vue.component('only-volunteer', OnlyVolunteer);
window.Vue.component('question-block', QuestionBlock);
window.Vue.component('radio-input', RadioInput);
window.Vue.component('radio-group', RadioGroup);

window.Vue.component('attestation-form', AttestationForm);
window.Vue.component('actionability-basic-form', ActionabilityBasicForm);
window.Vue.component('dosage-basic-form', DosageBasicForm);
window.Vue.component('baseline-basic-form', BaselineBasicForm);
window.Vue.component('baseline-genetic-form', BaselineGeneticForm);
window.Vue.component('gene-basic-form', GeneBasicForm);
window.Vue.component('somatic-basic-form', SomaticBasicForm);
window.Vue.component('variant-basic-form', VariantBasicForm);
window.Vue.component('date-field', DateField)
window.Vue.component('hypothesis-link', HypothesisLink)
window.Vue.component('training-session-list', TrainingSessionList)
window.Vue.component('training-session-detail', TrainingSessionDetail)

import RichTextEditor from './components/RichTextEditor'
window.Vue.component('rich-text-editor', RichTextEditor)

import Alerts from './components/Alerts'
window.Vue.component('alerts', Alerts)

window.Vue.filter('ucfirst', s => {
    if (typeof s !== 'string') return ''
    return s.charAt(0).toUpperCase() + s.slice(1)
})

Vue.filter('formatDate', function(dateString, format = 'YYYY-MM-DD HH:mm') {
    if (dateString === null) {
        return null;
    }

    return moment(dateString).format(format)
})

window.Vue.filter('boolToHuman', val => val ? 'Yes' : 'No')

import store from './store/index'
import { mapActions, mapGetters } from 'vuex'

function evaluate(el, binding, vnode) {
    console.log(binding);
}

function clearSessionStorage()
{
    sessionStorage.removeItem('user');
    sessionStorage.removeItem('impersonatable-users');
}

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 if (document.getElementById('app')) {
     const app = new window.Vue({
         el: '#app',
         store: store,
         computed: {
             infoMessages() {
                 console.log('getInfoMessages');
                 return this.$store.state.messages.info
             }
         },
         methods: {
             ...mapActions([
                 'fetchUser',
             ]),
             clearSessionStorage() {
                 clearSessionStorage();
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
    panels = await await getAllExpertPanels();
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
                // Update options for expert panel selection
                const activityPanels = panels.filter(panel => {
                    return panel.curation_activity_id == question.value &&
                        panel.accepting_volunteers == 1
                });
                const panelQuestion = question.parentElement.parentElement.parentElement.querySelector('select.panel-question');
                clearChildren(panelQuestion);
                panelQuestion.appendChild(createOption({ value: '', label: 'Select...' }))
                activityPanels.forEach(panel => panelQuestion.appendChild(createOption({ value: panel.id, label: panel.name })))
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
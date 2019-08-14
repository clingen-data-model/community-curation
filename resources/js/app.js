require('./bootstrap');

require('sirs-skiptrigger')
require('mutually-exclusive')
window.datepicker = require('bootstrap-datepicker');
window.timepicker = require('timepicker');
window.Vue = require('vue');

import BootstrapVue from 'bootstrap-vue'


Vue.use(BootstrapVue)

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
window.Vue.component('volunteer-index', VolunteerIndex);
window.Vue.component('volunteer-detail', VolunteerDetail);

window.Vue.filter('ucfirst', s => {
    if (typeof s !== 'string') return ''
    return s.charAt(0).toUpperCase() + s.slice(1)
})

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new window.Vue({
    el: '#app',
    mounted() {
    }
});


function clearChildren(el) {
    let child = el.lastElementChild;
    while (child) {
        el.removeChild(child);
        child = el.lastElementChild;
    }
}

function createOption({value, label}) {
    let option  = document.createElement('option');
    option.setAttribute('value', value);
    option.innerText = label;
    return option;
}

async function getPanels() {
    return await axios.get('/api/expert-panels')
        .then(response => {
            panels = response.data.data
        })
        .catch(error => {
            console.log(error);
        });
}

async function getCurationActivities() {
    return await axios.get('/api/curation-activities')
        .then(response => {
            curationActivities = response.data.data
        })
        .catch(error => {
            console.log(error);
        });
}
 

let curationActivities = getCurationActivities();
curationActivities.then(() => console.log(window.curationActivities))

let panels = getPanels();
panels.then( () => {
    Array.from(document.querySelectorAll('select.curation-activity-question'))
        .forEach(question => {
            question.disabled = false;
        })
});

document.addEventListener('DOMContentLoaded', () => {
    const curationActivityQuestions = Array.from(document.querySelectorAll('select.curation-activity-question'));
    
    curationActivityQuestions
        .forEach(question => {
            question.addEventListener('change', (evt) => {
                // Update options for expert panel selection
                const activityPanels = panels.filter(panel => panel.curation_activity_id == question.value);
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
            console.log(document.querySelector('[name=curation_activity_1]').value)
            const availableActivities = curationActivities.filter(activity => {
                return activity.id != evt.target.value 
                    && activity.id != document.querySelector('[name=curation_activity_1]').value
            })
            console.log(availableActivities)

            const ca3 = document.querySelector('[name=curation_activity_3]')
            clearChildren(ca3);
            ca3.appendChild(createOption({ value: '', label: 'Select...' }))
            availableActivities.forEach(activity => ca3.appendChild(createOption({ value: activity.id, label: activity.name })))
        });
    }
});


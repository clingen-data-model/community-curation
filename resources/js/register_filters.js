import Vue from 'vue';
window.Vue = Vue;

window.Vue.filter('ucfirst', s => {
    if (typeof s !== 'string') return ''
    return s.charAt(0).toUpperCase() + s.slice(1)
})

window.Vue.filter('formatDate', function(dateString, format = 'YYYY-MM-DD HH:mm') {
    if (dateString === null) {
        return null;
    }

    return moment(dateString).format(format)
})

window.Vue.filter('boolToHuman', val => val ? 'Yes' : 'No')
window.Vue = require('vue');

/**
 * Import all bootstrap view
 */
// import {BootstrapVue} from 'bootstrap-vue';
// window.Vue.use(BootstrapVue);

/**
 * Import Plugins
 */
import { 
    BadgePlugin, 
    CardPlugin, 
    CollapsePlugin,
    DropdownPlugin, 
    FormDatepickerPlugin,
    FormInputPlugin,
    FormTimepickerPlugin,
    IconsPlugin, 
    InputGroupPlugin,
    ModalPlugin, 
    PaginationPlugin, 
    PopoverPlugin, 
    ProgressPlugin, 
    TabsPlugin, 
    TablePlugin 
} from 'bootstrap-vue';

window.Vue.use(BadgePlugin);
window.Vue.use(CardPlugin);
window.Vue.use(CollapsePlugin);
window.Vue.use(DropdownPlugin);
window.Vue.use(FormDatepickerPlugin);
window.Vue.use(FormInputPlugin);
window.Vue.use(FormTimepickerPlugin);
window.Vue.use(IconsPlugin);
window.Vue.use(InputGroupPlugin);
window.Vue.use(ModalPlugin);
window.Vue.use(PaginationPlugin);
window.Vue.use(PopoverPlugin);
window.Vue.use(ProgressPlugin);
window.Vue.use(TabsPlugin);
window.Vue.use(TablePlugin);

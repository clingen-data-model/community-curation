import Vue from 'vue';
window.Vue = Vue;

/**
 * Bootstrap Vue plugins
 */
import {
    BadgePlugin,
    CardPlugin,
    CollapsePlugin,
    DropdownPlugin,
    FormCheckboxPlugin,
    FormDatepickerPlugin,
    FormInputPlugin,
    FormTimepickerPlugin,
    FormRadioPlugin,
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
window.Vue.use(FormCheckboxPlugin);
window.Vue.use(FormDatepickerPlugin);
window.Vue.use(FormInputPlugin);
window.Vue.use(FormRadioPlugin);
window.Vue.use(FormTimepickerPlugin);
window.Vue.use(IconsPlugin);
window.Vue.use(InputGroupPlugin);
window.Vue.use(ModalPlugin);
window.Vue.use(PaginationPlugin);
window.Vue.use(PopoverPlugin);
window.Vue.use(ProgressPlugin);
window.Vue.use(TabsPlugin);
window.Vue.use(TablePlugin);

/**
 * PrimeVue 2 (Vue 2 compatible) — installed alongside Bootstrap Vue.
 * Components will replace BV equivalents in Stage 2.
 */
import PrimeVue from 'primevue/config';
import Tooltip from 'primevue/tooltip';

// Components used in Stage 2 replacements
import Button from 'primevue/button';
import Calendar from 'primevue/calendar';
import Card from 'primevue/card';
import Checkbox from 'primevue/checkbox';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Menu from 'primevue/menu';
import OverlayPanel from 'primevue/overlaypanel';
import Paginator from 'primevue/paginator';
import Panel from 'primevue/panel';
import ProgressBar from 'primevue/progressbar';
import RadioButton from 'primevue/radiobutton';
import TabPanel from 'primevue/tabpanel';
import TabView from 'primevue/tabview';
import Tag from 'primevue/tag';

window.Vue.use(PrimeVue);
window.Vue.directive('tooltip', Tooltip);

window.Vue.component('PButton', Button);
window.Vue.component('PCalendar', Calendar);
window.Vue.component('PCard', Card);
window.Vue.component('PCheckbox', Checkbox);
window.Vue.component('PColumn', Column);
window.Vue.component('PDataTable', DataTable);
window.Vue.component('PDialog', Dialog);
window.Vue.component('PInputText', InputText);
window.Vue.component('PMenu', Menu);
window.Vue.component('POverlayPanel', OverlayPanel);
window.Vue.component('PPaginator', Paginator);
window.Vue.component('PPanel', Panel);
window.Vue.component('PProgressBar', ProgressBar);
window.Vue.component('PRadioButton', RadioButton);
window.Vue.component('PTabPanel', TabPanel);
window.Vue.component('PTabView', TabView);
window.Vue.component('PTag', Tag);

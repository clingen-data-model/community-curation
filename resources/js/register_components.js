window.Vue = require('vue');

/**
 * Global components
 */
window.Vue.component('non-volunteer', () =>
    import ( /* webpackChunkName: "global-components" */ './components/NonVolunteer'));

window.Vue.component('only-volunteer', () =>
    import ( /* webpackChunkName: "global-components" */ './components/OnlyVolunteer'));
window.Vue.component('global-progress', () =>
    import ( /* webpackChunkName: "global-components" */ './components/GlobalProgress'));
window.Vue.component('delete-button', () =>
    import ( /* webpackChunkName: "global-components" */ './components/DeleteButton'));
window.Vue.component('hypothesis-link', () =>
    import ( /* webpackChunkName: "global-components" */ './components/HypothesisLink'));
window.Vue.component('impersonate-control', () =>
    import ( /* webpackChunkName: "global-components" */ './components/ImpersonateControl'));
window.Vue.component('alerts', () =>
    import ( /* webpackChunkName: "global-components" */ './components/Alerts'));
window.Vue.component('help-button', () =>
    import ( /* webpackChunkName: "global-components" */ './components/HelpButton'));

window.Vue.component('rich-text', () =>
    import ( /* webpackChunkName: "rich-text" */ './components/RichTextEditor'));

/**
 * Layout components
 */
window.Vue.component('row', () =>
    import ( /* webpackChunkName: "global-components" */ './components/layout/Row'));
window.Vue.component('column', () =>
    import ( /* webpackChunkName: "global-components" */ './components/layout/Col'));

/**
 * Curation activities
 */
window.Vue.component('curation-activity-list', () =>
    import ( /* webpackChunkName: "curation-activity-list" */ './components/curation_activities/CurationActivityList'));
window.Vue.component('curation-activity-detail', () =>
    import ( /* webpackChunkName: "curation-activity-detail" */ './components/curation_activities/CurationActivityDetail'));

/**
 * Curation groups
 */
window.Vue.component('curation-group-list', () =>
    import ( /* webpackChunkName: "curation-group-list" */ './components/curation_groups/CurationGroupList'));
window.Vue.component('curation-group-detail', () =>
    import ( /* webpackChunkName: "curation-group-detail" */ './components/curation_groups/CurationGroupDetail'));

/**
 * Form components
 */
window.Vue.component('question-block', () =>
    import ( /* webpackChunkName: "form-components" */ './components/forms/QuestionBlock'));
window.Vue.component('radio-input', () =>
    import ( /* webpackChunkName: "form-components" */ './components/forms/inputs/RadioInput'));
window.Vue.component('radio-group', () =>
    import ( /* webpackChunkName: "form-components" */ './components/forms/inputs/RadioGroup'));
window.Vue.component('validation-error', () =>
    import ( /* webpackChunkName: "form-components" */ './components/ValidationError'));


window.Vue.component('date-field', () =>
    import ( /* webpackChunkName: "form-components" */ './components/DateField'));


/**
 * Volunteer components
 */
window.Vue.component('volunteer-index', () =>
    import ( /* webpackChunkName: "volunteer-index" */ './components/volunteers/VolunteerIndex'));
window.Vue.component('volunteer-detail', () =>
    import ( /* webpackChunkName: "volunteer-detail" */ './components/volunteers/VolunteerDetail'));
window.Vue.component('assignment-form', () =>
    import ( /* webpackChunkName: "assignment-form" */ './components/assignments/AssignmentForm'));

/**
 * Attestation Forms
 */
window.Vue.component('attestation-form', () =>
    import ( /* webpackChunkName: "attestation-form" */ './components/attestations/AttestationForm'))
window.Vue.component('actionability-basic-form', () =>
    import ( /* webpackChunkName: "actionability-basic-form" */ './components/attestations/forms/ActionabilityBasic'))
window.Vue.component('dosage-basic-form', () =>
    import ( /* webpackChunkName: "dosage-basic-form" */ './components/attestations/forms/DosageBasic'));
window.Vue.component('baseline-basic-form', () =>
    import ( /* webpackChunkName: "baseline-basic-form" */ './components/attestations/forms/BaselineBasic'));
window.Vue.component('baseline-genetic-form', () =>
    import ( /* webpackChunkName: "baseline-genetic-form" */ './components/attestations/forms/BaselineGenetic'));
window.Vue.component('gene-basic-form', () =>
    import ( /* webpackChunkName: "gene-basic-form" */ './components/attestations/forms/GeneBasic'));
window.Vue.component('somatic-basic-form', () =>
    import ( /* webpackChunkName: "somatic-basic-form" */ './components/attestations/forms/SomaticBasic'));
window.Vue.component('variant-basic-form', () =>
    import ( /* webpackChunkName: "variant-basic-form" */ './components/attestations/forms/VariantBasic'));

/**
 * Training Session
 */
window.Vue.component('training-session-list', () =>
    import ( /* webpackChunkName: "training-session-list" */ './components/training_sessions/TrainingSessionList'));
window.Vue.component('training-session-detail', () =>
    import ( /* webpackChunkName: "training-session-detail" */ './components/training_sessions/TrainingSessionDetail'));

/**
 * Reports
 */
window.Vue.component('report-form', () =>
    import ( /* webpackChunkName: "report-form" */ './components/reports/ReportForm'));

/**
 * Notes
 */
window.Vue.component('notes-list', () =>
    import ( /* webpackChunkName: "notes-list" */ './components/notes/NotesList'));

/**
 * Faqs
 */
window.Vue.component('faq-list', () =>
    import ( /* webpackChunkName: "faq-list" */ './components/faq/FaqList'));

/**
 * Admin
 */
window.Vue.component('logged-in-users-list', () =>
    import ( /* webpackChunkName: "logged-in-users-list" */ './components/admin/LoggedInUsersList'));
window.Vue.component('activity-metrics', () =>
    import ( /* webpackChunkName: "activity-metrics" */ './components/admin/ActivityMetrics'));
window.Vue.component('global-metrics', () =>
    import ( /* webpackChunkName: "global-metrics" */ './components/admin/GlobalMetrics'));
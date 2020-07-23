window.Vue = require('vue');

/**
 * Global components
 */
import NonVolunteer from './components/NonVolunteer';
window.Vue.component('non-volunteer', NonVolunteer);
import OnlyVolunteer from './components/OnlyVolunteer';
window.Vue.component('only-volunteer', OnlyVolunteer);
import GlobalProgress from './components/GlobalProgress'
window.Vue.component('global-progress', GlobalProgress);
import DeleteButton from './components/DeleteButton'
window.Vue.component('delete-button', DeleteButton);
import HypothesisLink from './components/HypothesisLink'
window.Vue.component('hypothesis-link', HypothesisLink)
import ImpersonateControl from './components/ImpersonateControl'
window.Vue.component('impersonate-control', ImpersonateControl);
import RichTextEditor from './components/RichTextEditor'
window.Vue.component('rich-text-editor', RichTextEditor)
import Alerts from './components/Alerts'
window.Vue.component('alerts', Alerts)

import HelpButton from './components/HelpButton'
window.Vue.component('help-button', HelpButton)


/**
 * Layout components
 */
import Row from './components/layout/Row'
window.Vue.component('row', Row);
import Column from './components/layout/Col'
window.Vue.component('column', Column);

/**
 * Curation activitys
 */
import CurationActivityList from './components/curation_activities/CurationActivityList';
window.Vue.component('curation-activity-list', CurationActivityList);
import CurationActivityDetail from './components/curation_activities/CurationActivityDetail';
window.Vue.component('curation-activity-detail', CurationActivityDetail);

/**
 * Curation groups
 */
import CurationGroupList from './components/curation_groups/CurationGroupList';
window.Vue.component('curation-group-list', CurationGroupList);
import CurationGroupDetail from './components/curation_groups/CurationGroupDetail';
window.Vue.component('curation-group-detail', CurationGroupDetail);


/**
 * Form components
 */
import QuestionBlock from './components/forms/QuestionBlock'
window.Vue.component('question-block', QuestionBlock);
import RadioInput from './components/forms/inputs/RadioInput'
window.Vue.component('radio-input', RadioInput);
import RadioGroup from './components/forms/inputs/RadioGroup'
window.Vue.component('radio-group', RadioGroup);
import ValidationError from './components/ValidationError';
window.Vue.component('validation-error', ValidationError);

import DateField from './components/DateField'
window.Vue.component('date-field', DateField)


/**
 * Volunteer components
 */
import VolunteerIndex from './components/volunteers/VolunteerIndex';
window.Vue.component('volunteer-index', VolunteerIndex);
import VolunteerDetail from './components/volunteers/VolunteerDetail';
window.Vue.component('volunteer-detail', VolunteerDetail);
import AssignmentForm from './components/assignments/AssignmentForm';
window.Vue.component('assignment-form', AssignmentForm);

    /**
     * Attestation Forms
     */
    import AttestationForm from './components/attestations/AttestationForm'
    window.Vue.component('attestation-form', AttestationForm);
    import ActionabilityBasicForm from './components/attestations/forms/ActionabilityBasic'
    window.Vue.component('actionability-basic-form', ActionabilityBasicForm);
    import DosageBasicForm from './components/attestations/forms/DosageBasic'
    window.Vue.component('dosage-basic-form', DosageBasicForm);
    import BaselineBasicForm from './components/attestations/forms/BaselineBasic'
    window.Vue.component('baseline-basic-form', BaselineBasicForm);
    import BaselineGeneticForm from './components/attestations/forms/BaselineGenetic'
    window.Vue.component('baseline-genetic-form', BaselineGeneticForm);
    import GeneBasicForm from './components/attestations/forms/GeneBasic'
    window.Vue.component('gene-basic-form', GeneBasicForm);
    import SomaticBasicForm from './components/attestations/forms/SomaticBasic'
    window.Vue.component('somatic-basic-form', SomaticBasicForm);
    import VariantBasicForm from './components/attestations/forms/VariantBasic'
    window.Vue.component('variant-basic-form', VariantBasicForm);
    
    
/**
 * Training Session
 */
import TrainingSessionList from './components/training_sessions/TrainingSessionList'
window.Vue.component('training-session-list', TrainingSessionList)
import TrainingSessionDetail from './components/training_sessions/TrainingSessionDetail'
window.Vue.component('training-session-detail', TrainingSessionDetail)


/**
 * Reports
 */
import ReportForm from './components/reports/ReportForm';
window.Vue.component('report-form', ReportForm);


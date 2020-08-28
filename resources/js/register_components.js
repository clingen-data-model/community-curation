window.Vue = require('vue');

/**
 * Global components
 */
window.Vue.component('non-volunteer', resolve => {
    import(/* webpackChunkName: "non-volunteer" */ './components/NonVolunteer')
        .then(NonVolunteer => {
            resolve(NonVolunteer.default);
        });
});

window.Vue.component('only-volunteer', resolve => {
    import(/* webpackChunkName: "only-volunteer" */ './components/OnlyVolunteer')
        .then(comp => {
            resolve(comp.default);
        });
});
window.Vue.component('global-progress', resolve => {
    import(/* webpackChunkName: "global-progress" */ './components/GlobalProgress')
        .then(comp => {
            resolve(comp.default)
        })
})
window.Vue.component('delete-button', resolve => {
    import(/* webpackChunkName: "delete-button" */ './components/DeleteButton')
        .then(comp => {
            resolve(comp.default)
        })
})
window.Vue.component('hypothesis-link', resolve => {
    import(/* webpackChunkName: "hypothesis-link" */ './components/HypothesisLink')
        .then(comp => {
            resolve(comp.default)
        })
})
window.Vue.component('impersonate-control', resolve => {
    import(/* webpackChunkName: "impersonate-control" */ './components/ImpersonateControl')
        .then(comp => {
            resolve(comp.default)
        })
})
window.Vue.component('rich-text', resolve => {
    import(/* webpackChunkName: "rich-text" */ './components/RichTextEditor')
        .then(comp => {
            resolve(comp.default)
        })
})
window.Vue.component('alerts', resolve => {
    import(/* webpackChunkName: "alerts" */ './components/Alerts')
        .then(comp => {
            resolve(comp.default)
        })
})


window.Vue.component('help-button', resolve => {
    import(/* webpackChunkName: "help-button" */ './components/HelpButton')
        .then( comp => {
            resolve(comp.default);
        });
});


/**
 * Layout components
 */
 
window.Vue.component('row', resolve => {
    import(/* webpackChunkName: "row" */ './components/layout/Row')
        .then( comp => {
            resolve(comp.default);
        });
});

window.Vue.component('column', resolve => {
    import(/* webpackChunkName: "column" */ './components/layout/Col')
        .then( comp => {
            resolve(comp.default);
        });
});

/**
 * Curation activities
 */
window.Vue.component('curation-activity-list', resolve => {
    import(/* webpackChunkName: "curation-activity-list" */ './components/curation_activities/CurationActivityList')
        .then(CurationActivityList => {
            resolve(CurationActivityList.default);
        });
});

window.Vue.component('curation-activity-detail', resolve => {
    import(/* webpackChunkName: "curation-activity-detail" */ './components/curation_activities/CurationActivityDetail')
        .then(CurationActivityDetail => {
            resolve(CurationActivityDetail.default);
        });
});

/**
 * Curation groups
 */
window.Vue.component('curation-group-list', resolve => {
    import(/* webpackChunkName: "curation-group-list" */ './components/curation_groups/CurationGroupList')
        .then(comp => {
            resolve(comp.default);
        });
});
window.Vue.component('curation-group-detail', resolve => {
    import(/* webpackChunkName: "curation-group-detail" */ './components/curation_groups/CurationGroupDetail')
        .then(comp => {
            resolve(comp.default);
        });
});

/**
 * Form components
 */

window.Vue.component('question-block', resolve => {
    import(/* webpackChunkName: "question-block" */ './components/forms/QuestionBlock')
        .then( comp => {
            resolve(comp.default);
        });
});;

window.Vue.component('radio-input', resolve => {
    import(/* webpackChunkName: "radio-input" */ './components/forms/inputs/RadioInput')
        .then( comp => {
            resolve(comp.default);
        });
});;

window.Vue.component('radio-group', resolve => {
    import(/* webpackChunkName: "radio-group" */ './components/forms/inputs/RadioGroup')
        .then( comp => {
            resolve(comp.default);
        });
});;

window.Vue.component('validation-error', resolve => {
    import(/* webpackChunkName: "validation-error" */ './components/ValidationError')
        .then( comp => {
            resolve(comp.default);
        });
});;


window.Vue.component('date-field', resolve => {
    import(/* webpackChunkName: "date-field', resolve" */ './components/DateField')
        .then( comp => {
            resolve(comp.default);
        });
});


/**
 * Volunteer components
 */
window.Vue.component('volunteer-index', resolve => {
    import(/* webpackChunkName: "volunteer-index" */ './components/volunteers/VolunteerIndex')
        .then(comp => {
            resolve(comp.default);
        });
});
window.Vue.component('volunteer-detail', resolve => {
    import(/* webpackChunkName: "volunteer-detail" */ './components/volunteers/VolunteerDetail')
        .then(comp => {
            resolve(comp.default);
        });
});
window.Vue.component('assignment-form', resolve => {
    import(/* webpackChunkName: "assignment-form" */ './components/assignments/AssignmentForm')
        .then(comp => {
            resolve(comp.default);
        });
});

    /**
     * Attestation Forms
     */
    window.Vue.component('attestation-form', resolve => {
        import(/* webpackChunkName: "attestation-form" */ './components/attestations/AttestationForm')
        .then( comp => {
            resolve(comp.default)
        })
    })

    window.Vue.component('actionability-basic-form', resolve => {
        import(/* webpackChunkName: "actionability-basic-form" */ './components/attestations/forms/ActionabilityBasic')
        .then( comp => {
            resolve(comp.default)
        })
    })

    window.Vue.component('dosage-basic-form', resolve => {
        import(/* webpackChunkName: "dosage-basic-form" */ './components/attestations/forms/DosageBasic')
        .then( comp => {
            resolve(comp.default);
        });
    });
    window.Vue.component('baseline-basic-form', resolve => {
        import(/* webpackChunkName: "baseline-basic-form" */ './components/attestations/forms/BaselineBasic')
        .then( comp => {
            resolve(comp.default);
        });
    });
    window.Vue.component('baseline-genetic-form', resolve => {
        import(/* webpackChunkName: "baseline-genetic-form" */ './components/attestations/forms/BaselineGenetic')
        .then( comp => {
            resolve(comp.default);
        });
    });
    window.Vue.component('gene-basic-form', resolve => {
        import(/* webpackChunkName: "gene-basic-form" */ './components/attestations/forms/GeneBasic')
        .then( comp => {
            resolve(comp.default);
        });
    });
    window.Vue.component('somatic-basic-form', resolve => {
        import(/* webpackChunkName: "somatic-basic-form" */ './components/attestations/forms/SomaticBasic')
        .then( comp => {
            resolve(comp.default);
        });
    });
    window.Vue.component('variant-basic-form', resolve => {
        import(/* webpackChunkName: "variant-basic-form" */ './components/attestations/forms/VariantBasic')
        .then( comp => {
            resolve(comp.default);
        });
    });
    
    
/**
 * Training Session
 */
 
window.Vue.component('training-session-list', resolve => {
    import(/* webpackChunkName: "training-session-list" */ './components/training_sessions/TrainingSessionList')
        .then( comp => {
            resolve(comp.default);
        });
});

window.Vue.component('training-session-detail', resolve => {
    import(/* webpackChunkName: "training-session-detail" */ './components/training_sessions/TrainingSessionDetail')
        .then( comp => {
            resolve(comp.default);
        });
});


/**
 * Reports
 */
 
window.Vue.component('report-form', resolve => {
    import(/* webpackChunkName: "report-form" */ './components/reports/ReportForm')
        .then( comp => {
            resolve(comp.default);
        });
});;

/**
 * Notes
 */
 
window.Vue.component('notes-list', resolve => {
    import(/* webpackChunkName: "notes-list" */ './components/notes/NotesList')
        .then( comp => {
            resolve(comp.default);
        });
});;

/**
 * Faqs
 */
 
window.Vue.component('faq-list', resolve => {
    import(/* webpackChunkName: "faq-list" */ './components/faq/FaqList')
        .then( comp => {
            resolve(comp.default);
        });
});;

/**
 * Admin
 */
 
window.Vue.component('logged-in-users-list', resolve => {
    import(/* webpackChunkName: "logged-in-users-list" */ './components/admin/LoggedInUsersList')
        .then( comp => {
            resolve(comp.default);
        });
});;


window.Vue.component('activity-metrics', resolve => {
    import(/* webpackChunkName: "activity-metrics" */ './components/admin/ActivityMetrics')
        .then( comp => {
            resolve(comp.default);
        });
});;
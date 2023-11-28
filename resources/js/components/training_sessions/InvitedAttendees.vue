<template>
        <section>
            <header class="d-flex justify-content-between align-items-start">
                <h4>
                    Attendees <small>({{attendees.length}})</small> 
                    <button 
                        class="btn btn-default material-icons" 
                        :class="{rotate: loadingAttendees}" 
                        @click="getAttendees"
                    >
                        cached
                    </button>
                </h4>
                <button class="btn btn-light border btn-xs" @click="showEmailForm = !showEmailForm">
                    {{showEmailForm ? `Cancel email` : `Email attendees`}}
                </button>                
            </header>

            <transition name="slide-fade">
                <attendee-email-form 
                    :trainingSession="trainingSession" 
                    :attendees="attendees"
                    v-show="showEmailForm" 
                    class="mb-2"
                    @sending="showEmailForm = false"
                    @sent="showEmailForm = false"
                    @canceled="showEmailForm = false"
                ></attendee-email-form>
            </transition>
            <transition name="slide-fade">
                <div v-show="!showEmailForm">
                    <div v-if="hasAttendees" style="overflow-y: scroll; border-bottom: 1px solid #eee">
                        <b-table :fields="attendeeFields" :items="attendees" 
                            small 
                            sticky-header="305px"
                            no-border-collapse
                            class="border-bottom"
                        >
                            <template v-slot:cell(first_name)="{item}">
                                <a :href="`/volunteers/${item.id}`">{{item.first_name}}</a>
                            </template>
                            <template v-slot:cell(last_name)="{item}">
                                <a :href="`/volunteers/${item.id}`">{{item.last_name}}</a>
                            </template>
                            <template v-slot:cell(id)="{item}">
                                <button v-if="sessionStarted && !item.training_complete"
                                    @click="markTrainingComplete(item)"
                                    class="btn btn-default btn-xs border" 
                                >
                                    Mark Training Complete
                                </button>
                                <small class="text-muted" v-show="item.training_complete">Training Complete</small>
                                <span v-if="item.already_clingen_member" :id="`already-clingen-member-${item.id}`">
                                    <b-icon 
                                        icon="person-badge-fill" 
                                        style="color: SteelBlue!important" 
                                    ></b-icon>
                                    <b-popover :target="`already-clingen-member-${item.id}`" triggers="hover">
                                        <strong>Already a ClinGen member.</strong>
                                        <div v-if="item.already_member_groups">
                                            <strong>Curation Groups:</strong>
                                            <ul class="mb-0">
                                                <li v-for="ep in item.already_member_groups" :key="ep.id">{{ep.name}}</li>
                                            </ul>
                                        </div>
                                    </b-popover>
                                </span>
                            </template>
                        </b-table>
                    </div>
                    <div v-else class="alert alert-light border">
                            <span v-if="loadingAttendees">Loading...</span>
                            <span v-else>No volunteers have been invited to attend.</span>
                    </div>
                </div>                
            </transition>

        </section>
</template>
<script>
import {mapMutations} from 'vuex'

import getAttendees from '../../resources/training_sessions/attendees/get_attendees'
import markTrainingComplete from '../../resources/trainings/mark_training_complete'

import AttendeeEmailForm from './AttendeeEmailForm'

export default {
    components: {
        AttendeeEmailForm,
    },
    props: {
        trainingSession: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            attendees: [],
            loadingAttendees: false,
            showEmailForm: false,            
            fields: [
                {
                    key: 'first_name',
                    label: 'First Name',
                    sortable: true,
                },
                {
                    key: 'last_name',
                    label: 'Last Name',
                    sortable: true,
                },
                {
                    key: 'email',
                    label: 'Email',
                    sortable: true
                },
                {
                    key: 'assignments[0].date_assigned',
                    label: 'Date Assigned',
                    sortable: true,
                    formatter: (value, key, item) => {
                        if (item.assignments[0]) {
                            return this.$root.$options.filters.formatDate(item.assignments[0].date_assigned, 'YYYY-MM-DD')
                        } else {
                            console.info('missing assignments',item)
                        }
                    }
                },
                
            ],
        }
    },
    watch: {
        trainingSession() {
            this.getAttendees();
        },
    },
    computed: {
        sessionStarted() {
            return this.trainingSession.starts_at.isBefore(this.currentDateTime)
        },
        hasAttendees() {
            return this.attendees.length > 0;
        },
        attendeeFields() {
            return this.fields.concat([{
                key: 'id',
                label: ''
            }])
        },

    },
    methods: {
        ...mapMutations('messages', [
            'addInfo'
        ]),
        async getAttendees() {
            this.loadingAttendees = true;
            this.attendees = await getAttendees(this.trainingSession);
            this.loadingAttendees = false;
        },
        markTrainingComplete (item) {
            this.$set(item, 'training_complete', true)
            markTrainingComplete(item.assignments[0].user_aptitudes[0].id, moment())
                .then(response => {
                    this.addInfo('Training marked completed for '+item.first_name+' '+item.last_name)
                });
        },

    }
}
</script>   
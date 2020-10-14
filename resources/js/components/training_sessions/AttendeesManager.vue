<style>
    tr.table-muted {
        background-color: #ddd;
        color: 999 !important;
    }
</style>
<template>
    <div>
        <section>
            <header>
                <button class="btn btn-light border btn-xs float-right" @click="showEmailForm = !showEmailForm">
                    {{showEmailForm ? `Cancel email` : `Email attendees`}}
                </button>                
                <h4>
                    Attendees <small>({{attendees.length}})</small> 
                    <button class="btn btn-default material-icons" :class="{rotate: loadingAttendees}" @click="getAttendees">cached</button>
                </h4>
            </header>

            <transition name="slide-fade">
                <attendee-email-form 
                    :trainingSession="trainingSession" 
                    :attendees="attendees"
                    v-show="showEmailForm" class="mb-2"
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
        <section class="mt-5">
            <header class="clearfix">
                <button class="btn btn-sm btn-light border float-right ml-2" @click="getSelectedEmails" :disabled="!canInvite">Copy emails</button>
                <button class="btn btn-sm btn-primary float-right" @click="inviteSelected" :disabled="!canInvite">{{inviteButtonText}}</button>
                <div class="float-right mr-2 mb-2 pr-2 border-right">
                    <b-form-input type="text" v-model="trainableFilter" class="form-control form-control-sm mr-1" placeholder="search first or last" debounce="250"></b-form-input>
                    <button @click="showInactiveTrainables = !showInactiveTrainables" class="btn btn-xs btn-default">
                        {{showInactiveTrainables ? 'Hide' : 'Show'}} inactive volunteers
                    </button>
                </div>
                <h5>
                    {{showInactiveTrainables ? 'All' : 'Active'}} volunteers who need {{trainingSession.topic.name}} training 
                    <small>({{filteredTrainable.length}})</small>
                    <button class="btn btn-default material-icons" :class="{rotate: loadingVolunteers}" @click="getTrainableVolunteers">cached</button>
                </h5>
            </header>

            <b-table :fields="inviteFields" :items="filteredTrainable" 
                v-if="trainableVolunteers.length > 0" 
                sort-by="assignments[0].date_assigned" 
                small
                no-border-collapse
                class="border-bottom"
                @row-clicked="handleInviteRowClick"
            >
                <template v-slot:cell(first_name)="{item}">
                    <a :href="`/volunteers/${item.id}`">{{item.first_name}}</a>
                </template>
                <template v-slot:cell(last_name)="{item}">
                    <a :href="`/volunteers/${item.id}`">{{item.last_name}}</a>
                </template>
                <template v-slot:cell(id)="{item}">
                    <div class="text-center">
                        <input type="checkbox" :value="item" v-model="selectedVolunteers">
                    </div>
                </template>
                <template v-slot:cell(training_sessions_count)="{item}">
                    <div
                        v-if="item.training_sessions_count > 0"
                        :id="`attendence-warning-${item.id}`"
                    >
                        <b-icon 
                            icon="exclamation-triangle-fill" 
                            style="color: #ffc107!important"
                            class="cursor-pointer"
                            @click.stop="showUpdateStatus = true; currentVolunteer = item"
                        ></b-icon>
                        <b-popover :target="`attendence-warning-${item.id}`" triggers="hover">
                            {{item.training_sessions_count}} previous
                            invite{{item.training_sessions_count > 1 ? `s` : ''}} 
                            to {{trainingSession.topic.name.toLowerCase()}} training
                        </b-popover>
                    </div>
                </template>
                <template v-slot:head(id)="data">
                    <div class="text-center">
                        <input type="checkbox" v-model="selectAll" @change="toggleAll">
                    </div>
                </template>
            </b-table>

            <div class="alert alert-light border mt-2 mb-2" v-else>
                <span v-if="loadingVolunteers">Loading...</span>
                <span v-else>There are no more volunteers who need this training at this time.</span>
            </div>
            <footer class="mt-1">
                <button class="btn btn-sm btn-light border float-right ml-2" @click="getSelectedEmails" :disabled="!canInvite">Copy emails</button>
                <button class="btn btn-sm btn-primary float-right" @click="inviteSelected" :disabled="!canInvite">{{inviteButtonText}}</button>
            </footer>
        </section>
        <b-modal v-model="showUpdateStatus" title="Update Volunteer Statuses">
            <table class="table table-striped table-sm">
                <tr>
                    <th>Name</th>
                    <th>Date Assigned</th>
                    <th>Invite #</th>
                    <th>
                        Status
                    </th>
                </tr>
                <tr v-for="vol in previouslyInvited" :key="vol.id">
                    <td>{{vol.name}}</td>
                    <td>{{vol.assignments[0].date_assigned | formatDate('YYYY-MM-DD')}}</td>
                    <td>{{vol.training_sessions_count}}</td>
                    <td>
                        <status-badge :assignment="vol.assignments[0]"
                            @assignmentsupdated="getTrainableVolunteers"
                        ></status-badge>
                    </td>
                </tr>
            </table>
        </b-modal>
    </div>
</template>
<script>
import moment from 'moment'
import inviteAttendees from '../../resources/training_sessions/attendees/invite'
import markTrainingComplete from '../../resources/trainings/mark_training_complete'
import getAttendees from '../../resources/training_sessions/attendees/get_attendees'
import getTrainableVolunteers from '../../resources/training_sessions/attendees/get_trainable_volunteers'
import AttendeeEmailForm from './AttendeeEmailForm'
import {mapMutations} from 'vuex'
import updateVolunteer from '../../resources/volunteers/update_volunteer'
import StatusBadge from '../assignments/StatusBadge'
import copyToClipboard from '../../browser/copyToClipboard'

export default {
    components: {
        AttendeeEmailForm,
        StatusBadge
    },
    props: {
        trainingSession: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            statusChange: 0,
            currentVolunteer: {assignments: []},
            test: false,
            attendees: [],
            selectedVolunteers: [],
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
            trainableVolunteers: [],
            loadingAttendees: false,
            loadingVolunteers: false,
            selectAll: false,
            showEmailForm: false,
            inviting: false,
            currentDateTime: moment(),
            trainableFilter: null,
            showInactiveTrainables: false,
            showUpdateStatus: false
        }
    },
    watch: {
        trainingSession() {
            this.getAttendees();
            this.getTrainableVolunteers();
        }
    },
    computed: {
        sessionStarted() {
            return this.trainingSession.starts_at.isBefore(this.currentDateTime)
        },
        hasAttendees() {
            return this.attendees.length > 0;
        },
        inviteFields() {
            return this.fields.concat([{
                    key: 'id',
                    label: ''
                },
            {
                key: 'training_sessions_count',
                label: ''
            }])
        },
        attendeeFields() {
            return this.fields.concat([{
                key: 'id',
                label: ''
            }])
        },
        canInvite() {
            return this.trainableVolunteers.length > 0 && this.selectedVolunteers.length > 0 && !this.inviting;
        },
        inviteButtonText() {
            return this.inviting ? 'Busy...' : 'Invite Selected'
        },
        activeTrainable() {
            if (!this.showInactiveTrainables) {
                return this.trainableVolunteers.filter(row => {
                    if (!row.assignments[0]) {
                        return false;
                    }
                    return ['unresponsive', 'declined', 'retired'].indexOf(row.assignments[0].status.name) == -1
                })
            }

            return this.trainableVolunteers;
        },
        filteredTrainable() {
            if (this.trainableFilter === null || this.trainableFilter === '') {
                return this.activeTrainable;
            }
            
            return this.activeTrainable
                    .filter(vol => {
                        return vol.first_name.toLowerCase().includes(this.trainableFilter.toLowerCase())
                            || vol.last_name.toLowerCase().includes(this.trainableFilter.toLowerCase())
                    });
        },
        previouslyInvited() {
            return this.filteredTrainable.filter(vol => vol.training_sessions_count > 0)
        }
    },
    methods: {
        ...mapMutations('messages', [
            'addInfo'
        ]),
        toggleAll() {
            if (this.selectAll) {
                this.selectedVolunteers = this.activeTrainable;
            } else {
                this.selectedVolunteers = [];
            }
        },
        inviteSelected() {
            const originalAttendees = JSON.parse(JSON.stringify(this.attendees));
            const originalTrainable = JSON.parse(JSON.stringify(this.trainableVolunteers));
            this.inviting = true;

            inviteAttendees(this.trainingSession, this.selectedVolunteers)
                .then(response => {
                    this.attendees = response.data.data;
                    this.addInfo('Invited '+this.selectedVolunteers.length+' volunteer'+(this.selectedVolunteers.length > 1 ? 's' : '')+' to the training session');
                    this.selectedVolunteers = [];
                    this.getTrainableVolunteers();
                    this.selectAll = false;
                })
                .catch(error => {
                    alert('There was a problem inviting attendees: '+error.response.statusText+'.');
                    this.attendees = originalAttendees;
                    this.trainableVolunteers = originalTrainable;
                    this.selectAll = false;
                })
                .then(() => this.inviting = false);
        },
        getSelectedEmails() {
            const emailString = this.selectedVolunteers.map(i => i.email).join(',');
            copyToClipboard(emailString)
                .then(() => {
                    this.addInfo('Email addresses for '+ this.selectedVolunteers.length + ' volunteers copied to your clipboard');
                })
        },
        async getAttendees() {
            this.loadingAttendees = true;
            this.attendees = await getAttendees(this.trainingSession);
            this.loadingAttendees = false;
        },
        async getTrainableVolunteers() {
            this.loadingVolunteers = true;
            this.trainableVolunteers = await getTrainableVolunteers(this.trainingSession);
            this.trainableVolunteers  = this.trainableVolunteers
                                        .map(row => {
                                            if (!row.assignments[0]) {
                                                return row
                                            }
                                            if (['unresponsive', 'declined', 'retired'].indexOf(row.assignments[0].status.name) > -1) {
                                                row._rowVariant = 'muted'
                                            }
                                            return row
                                        });
            this.loadingVolunteers = false;
        },
        handleInviteRowClick (item, index, evt) {
            // console.info('handleInviteRowClick', item, index, evt);
            const itemIdx = this.selectedVolunteers.indexOf(item)
            if (itemIdx > -1) {
                this.selectedVolunteers.splice(itemIdx, 1);
                return;
            }
            this.selectedVolunteers.push(item);
        },
        markTrainingComplete (item) {
            this.$set(item, 'training_complete', true)
            markTrainingComplete(item.assignments[0].user_aptitudes[0].id, moment())
                .then(response => {
                    this.addInfo('Training marked completed for '+item.first_name+' '+item.last_name)
                });
        },
        async markUnresponsive (volunteer) {
            const msg = `You are about to mark ${volunteer.name} unresponsive to their ${this.trainingSession.topic.name} variant curation activity assignment.  If you continue and the volunteer does not have other assignments their volunteer-status will be set to unresponsive. Do you want to continue?`
            if (confirm(msg)) {
                volunteer = await updateVolunteer(volunteer.id, {})
            }
        }
    },
    mounted () {
        setInterval(() => {
            this.currentDateTime = moment();
        }, 1000);
    }
}
</script>
<style>
    tr.table-muted {
        background-color: #ddd;
        color: 999 !important;
    }
</style>
<template>
    <div>
        <invited-attendees :training-session="trainingSession" ref="attendeesList"></invited-attendees>
        <section class="mt-5">
            <header class="d-flex justify-content-between">
                <h5>
                    {{showInactiveTrainables ? 'All' : 'Active'}} volunteers who need {{trainingSession.topic.name}} training 
                    <small>({{filteredTrainable.length}})</small>
                    <button class="btn btn-default material-icons" :class="{rotate: loadingVolunteers}" @click="getTrainableVolunteers">cached</button>
                </h5>
                <div class="d-flex align-items-start">
                    <div class=" mr-2 mb-2 pr-2 border-right">
                        <b-form-input type="text" v-model="trainableFilter" class="form-control form-control-sm mr-1" placeholder="search first or last" debounce="250"></b-form-input>
                        <button @click="showInactiveTrainables = !showInactiveTrainables" class="btn btn-xs btn-default">
                            {{showInactiveTrainables ? 'Hide' : 'Show'}} inactive volunteers
                        </button>
                    </div>

                    <button class="btn btn-sm btn-primary mr-2" @click="inviteSelected" :disabled="!canInvite">{{inviteButtonText}}</button>
                    <button class="btn btn-sm btn-light border  mr-2" @click="getSelectedEmails" :disabled="!canInvite">Copy emails</button>
                    <button class="btn btn-light border btn-sm" @click="showEmailForm = !showEmailForm" :disabled="!canInvite">
                        {{showEmailForm ? `Cancel email` : `Email selected`}}
                    </button>                
                </div>
            </header>

            <transition name="slide-fade">
                <attendee-email-form 
                    :trainingSession="trainingSession" 
                    :attendees="selectedVolunteers"
                    v-show="showEmailForm" 
                    class="mb-2"
                    @sending="showEmailForm = false"
                    @sent="showEmailForm = false"
                    @canceled="showEmailForm = false"
                ></attendee-email-form>
            </transition>

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
                    <span
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
                    </span>
                    <span v-if="item.already_clingen_member" :id="`already-clingen-member-${item.id}`">
                        <b-icon 
                            icon="person-badge-fill" 
                            style="color: SteelBlue!important" 
                        ></b-icon>
                        <b-popover :target="`already-clingen-member-${item.id}`" triggers="hover">
                            <strong>Already a ClinGen member of:</strong>
                            <ul class="mb-0">
                                <li v-for="ep in item.already_member_groups" :key="ep.id">{{ep.name}}</li>
                            </ul>
                        </b-popover>
                    </span>
                </template>
                <template v-slot:head(id)="">
                    <div class="text-center">
                        <input type="checkbox" v-model="selectAll" @change="toggleAll">
                    </div>
                </template>
            </b-table>

            <div class="alert alert-light border mt-2 mb-2" v-else>
                <span v-if="loadingVolunteers">Loading...</span>
                <span v-else>There are no more volunteers who need this training at this time.</span>
            </div>
            <footer class="mt-1 text-right">
                <button class="btn btn-sm btn-primary mr-2" @click="inviteSelected" :disabled="!canInvite">{{inviteButtonText}}</button>
                <button class="btn btn-sm btn-light border  mr-2" @click="getSelectedEmails" :disabled="!canInvite">Copy emails</button>
                <button class="btn btn-light border btn-sm" @click="showEmailForm = !showEmailForm" :disabled="!canInvite">
                    {{showEmailForm ? `Cancel email` : `Email selected`}}
                </button>                
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
import InvitedAttendees from './InvitedAttendees.vue'
import inviteAttendees from '../../resources/training_sessions/attendees/invite'
import getTrainableVolunteers from '../../resources/training_sessions/attendees/get_trainable_volunteers'
import {mapMutations} from 'vuex'
import updateVolunteer from '../../resources/volunteers/update_volunteer'
import StatusBadge from '../assignments/StatusBadge.vue'
import copyToClipboard from '../../browser/copyToClipboard'
import AttendeeEmailForm from './AttendeeEmailForm.vue'

export default {
    components: {
        AttendeeEmailForm,
        InvitedAttendees,
        StatusBadge,
    },
    props: {
        trainingSession: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            showEmailForm: false,
            statusChange: 0,
            currentVolunteer: {assignments: []},
            test: false,
            selectedVolunteers: [],
            trainableVolunteers: [],
            loadingAttendees: false,
            loadingVolunteers: false,
            selectAll: false,
            inviting: false,
            currentDateTime: moment(),
            trainableFilter: null,
            showInactiveTrainables: false,
            showUpdateStatus: false,
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
            // this.getAttendees();
            this.getTrainableVolunteers();
        }
    },
    computed: {
        sessionStarted() {
            return this.trainingSession.starts_at.isBefore(this.currentDateTime)
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
            // const originalAttendees = JSON.parse(JSON.stringify(this.attendees));
            const originalTrainable = JSON.parse(JSON.stringify(this.trainableVolunteers));
            this.inviting = true;

            inviteAttendees(this.trainingSession, this.selectedVolunteers)
                .then(response => {
                    this.attendees = response.data.data;
                    this.addInfo('Invited '+this.selectedVolunteers.length+' volunteer'+(this.selectedVolunteers.length > 1 ? 's' : '')+' to the training session');
                    this.selectedVolunteers = [];
                    this.getTrainableVolunteers();
                    this.selectAll = false;
                    this.$refs.attendeesList.getAttendees();
                })
                .catch(error => {
                    alert('There was a problem inviting attendees: '+error.response.statusText+'.');
                    // this.attendees = originalAttendees;
                    this.trainableVolunteers = originalTrainable;
                    this.selectAll = false;
                })
                .then(() => this.inviting = false);
        },
        getSelectedEmails() {
            const emailString = this.selectedVolunteers.map(i => i.email).join(';');
            copyToClipboard(emailString)
                .then(() => {
                    this.addInfo('Email addresses for '+ this.selectedVolunteers.length + ' volunteers copied to your clipboard');
                })
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
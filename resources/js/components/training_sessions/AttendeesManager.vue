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
                    <div v-if="hasAttendees">
                        <b-table :fields="fields" :items="attendees" small></b-table>
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
                <button class="btn btn-sm btn-primary float-right" @click="inviteSelected" :disabled="!canInvite">Invite Selected</button>
                <h5>
                    Volunteers who need {{trainingSession.topic.name}} training 
                    <small>({{trainableVolunteers.length}})</small>
                    <button class="btn btn-default material-icons" :class="{rotate: loadingVolunteers}" @click="getTrainableVolunteers">cached</button>
                </h5>
            </header>

            <b-table :fields="inviteFields" :items="trainableVolunteers" v-if="trainableVolunteers.length > 0" sort-by="assignments[0].date_assigned" small>
                <template v-slot:cell(id)="data">
                    <div class="text-center">
                        <input type="checkbox" :value="data.item" v-model="selectedVolunteers">
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
                <button class="btn btn-sm btn-primary float-right" @click="inviteSelected" :disabled="!canInvite">Invite Selected</button>
            </footer>
        </section>

    </div>
</template>
<script>
import moment from 'moment'
import inviteAttendees from '../../resources/training_sessions/attendees/invite'
import getAttendees from '../../resources/training_sessions/attendees/get_attendees'
import getTrainableVolunteers from '../../resources/training_sessions/attendees/get_trainable_volunteers'
import AttendeeEmailForm from './AttendeeEmailForm'
export default {
    components: {
        AttendeeEmailForm
    },
    props: {
        trainingSession: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
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
                    key: 'assignments[0].date_assigned',
                    label: 'Date Assigned',
                    sortable: true,
                    formatter: (value, key, item) => {
                         return this.$root.$options.filters.formatDate(item.assignments[0].date_assigned, 'YYYY-MM-DD')
                    }
                },
                
            ],
            trainableVolunteers: [],
            loadingAttendees: false,
            loadingVolunteers: false,
            selectAll: false,
            showEmailForm: true,
        }
    },
    watch: {
        trainingSession() {
            this.getAttendees();
            this.getTrainableVolunteers();
        }
    },
    computed: {
        hasAttendees() {
            return this.attendees.length > 0;
        },
        inviteFields() {
            return this.fields.concat([{
                    key: 'id',
                    label: ''
                }])
        },
        canInvite() {
            return this.trainableVolunteers.length > 0 && this.selectedVolunteers.length > 0;
        }
    },
    methods: {
        toggleAll() {
            if (this.selectAll) {
                this.selectedVolunteers = this.trainableVolunteers;
            } else {
                this.selectedVolunteers = [];
            }
        },
        inviteSelected() {
            const originalAttendees = JSON.parse(JSON.stringify(this.attendees));
            const originalTrainable = JSON.parse(JSON.stringify(this.trainableVolunteers));

            inviteAttendees(this.trainingSession, this.selectedVolunteers)
                .then(response => {
                    this.attendees = response.data;
                    this.selectedVolunteers = [];
                    this.getTrainableVolunteers();
                    this.selectAll = false;
                })
                .catch(error => {
                    alert('There was a problem inviting attendees: '+error.response.statusText+'.');
                    this.attendees = originalAttendees;
                    this.trainableVolunteers = originalTrainable;
                    this.selectAll = false;
                });
        },
        async getAttendees() {
            this.loadingAttendees = true;
            this.attendees = await getAttendees(this.trainingSession);
            this.loadingAttendees = false;
        },
        async getTrainableVolunteers() {
            this.loadingVolunteers = true;
            this.trainableVolunteers = await getTrainableVolunteers(this.trainingSession);
            this.loadingVolunteers = false;
        }
    },
    mounted () {

    }
}
</script>
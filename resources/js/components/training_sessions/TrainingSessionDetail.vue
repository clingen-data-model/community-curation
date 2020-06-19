<template>
    <div>
        <div class="card">
            <div class="card-header">
                <div class="float-right ">
                    <b-dropdown size="sm" text="Add to Calendar" variant="primary">
                        <b-dropdown-item 
                            v-for="(val, key) in trainingSession.calendar_links" :key="key"
                            :href="val" 
                            target="calendar-link"
                        >{{key}}</b-dropdown-item>
                        <b-dropdown-divider></b-dropdown-divider>
                        <b-dropdown-item @click="showCalendarHelp = true"><small>Learn More</small></b-dropdown-item>
                    </b-dropdown>
                    <button class="btn btn-sm btn-primary" @click="showEdit = true">Edit</button>
                </div>
                <h3 class="m-0">
                    <a href="/training-sessions">Training Sessions</a>
                    -
                    {{trainingSession.topic.name}}
                    on
                    {{trainingSession.starts_at | formatDate('YYYY-MM-DD \\at h:mm a')}}
                </h3>
            </div>
            <div class="card-body">
                <div v-if="show404" class="alert alert-warning">
                    The session you're looking for could not be found.
                </div>
                <section id="session-info">
                    <h4>Session Info</h4>
                    <row class="mb-2">
                        <column class="col-md-2 text-right">
                            <strong>URL</strong>
                        </column>
                        <column class="col-md-9">
                            {{trainingSession.url}}
                        </column>
                    </row>
                    <row class="mb-2">
                        <column class="col-md-2 text-right">
                            <strong>Duration</strong>
                        </column>
                        <column class="col-md-9">
                            {{duration}}
                        </column>
                    </row>
                    <row class="mb-2">
                        <column class="col-md-2 text-right">
                            <strong>Invite Message</strong>
                        </column>
                        <column class="col-md-9">
                            {{trainingSession.invite_message}}
                        </column>
                    </row>
                    <row class="mb-2">
                        <column class="col-md-2 text-right"><strong>Notes</strong></column>
                        <column class="col-md-9">{{trainingSession.notes}}</column>
                    </row>
                    <br>
                    <invite-email-preview :training-session="trainingSession"></invite-email-preview>
                </section>
                <section id="attendees" class="mt-5">
                    <attendees-manager :training-session="trainingSession"></attendees-manager>
                </section>
            </div>
        </div>
        <b-modal v-model="showEdit" size="lg" hide-footer>
            <training-session-form :training-session="trainingSession"
                :errors="errors"
                @saved="updateSession"
                @canceled="cancelUpdate"
            ></training-session-form>
        </b-modal>
        <b-modal v-model="showCalendarHelp" title="How to add this training session to your calendar">
            <b-modal-body>
                <dl>
                    <dt v-b-toggle.collapse-google><h5>Google, Yahoo, or web-based Outlook Calendar</h5></dt>
                    <dl class="pl-3"> 
                        <b-collapse id="collapse-google">
                            Clicking on the link for your web-based calendar link will open your calendar in another tab/window and prompt your to save the event.
                        </b-collapse>
                    </dl>

                    <dt v-b-toggle.collapse-apple-outlook><h5>Apple or Outlook Calendar</h5></dt>
                    <dl class="pl-3"> 
                        <b-collapse id="collapse-apple-outlook">Clicking the link will download an .ics (iCalendar) standard file. Your operating system should open the file in the appropriate calendar application.</b-collapse>
                    </dl>

                    <dt v-b-toggle.collapse-other><h5>Other calendar apps</h5></dt>
                    <dl class="pl-3">
                        <b-collapse id="collapse-other">
                            Most calendar apps support the open iCalendar format so you should  download and open the .ics file by clicking on 'Apple &amp; Outlook'
                        </b-collapse>
                    </dl>
                </dl>
            </b-modal-body>
        </b-modal>
    </div>
</template>
<script>
import updateTrainingSession from '../../resources/training_sessions/update';
import TrainingSessionForm from './TrainingSessionForm'
import AttendeesManager from './AttendeesManager'
import InviteEmailPreview from './InviteEmailPreview'
import moment from 'moment'

export default {
    components: {
        TrainingSessionForm,
        AttendeesManager,
        InviteEmailPreview
    },
    props: {
        id: {
            required: true,
            type: Number
        }
    },
    data() {
        return {
            trainingSession: {
                topic: {}
            },
            showEdit: false,
            errors: {},
            show404: false,
            showCalendarHelp: false,
            showEmailPreview: false,
        }
    },
    computed: {
        duration() {
            if (!this.trainingSession.starts_at || !this.trainingSession.ends_at ) {
                return '';
            }

            const diffInMinutes = this.trainingSession.ends_at.diff(this.trainingSession.starts_at, 'minutes');
            if (diffInMinutes > 60) {
                const hours = Math.floor(diffInMinutes/60);
                const minutes = diffInMinutes%60;
                let parts = [hours];
                parts.push((hours > 1) ? 'hours' : 'hour');
                parts.push(minutes);
                parts.push((minutes > 1) ? 'minutes' : 'minute');
                return parts.join(' ');
            }
            return `${diffInMinutes} minutes`
        }
    },
    methods: {
        updateSession(data) {
            this.errors = {};
            updateTrainingSession(this.trainingSession.id, data)
                .then(response => {
                    this.showEdit = false;
                    var session = response.data.data;
                    session.starts_at = moment(session.starts_at);
                    session.ends_at = moment(session.ends_at);
                    this.trainingSession = session;
                })
                .catch(error => {
                    if (error.response && error.response.status == 422) {
                        this.errors = error.response.data.errors
                    }
                    console.error(error);
                })
        },
        cancelUpdate() {
            this.errors = {};
            this.currentSession = {};
            this.showEdit = false;
        },
        addToCalendar() {
            alert('add to calendar')
        },
        async getTrainingSession ()
        {
            this.show404 = false;
            this.trainingSession = await window.axios.get('/api/training-sessions/'+this.id)
                                    .then(response => {
                                        let ses = response.data.data;
                                        ses.starts_at = moment(ses.starts_at)
                                        ses.ends_at = moment(ses.ends_at)
                                        return ses
                                    })
                                    .catch(error => {
                                        if (error.response.status == 404) {
                                            this.show404 = true;
                                            return
                                        }
                                        new Error(error);
                                    })
        }
    },
    mounted() {
        this.getTrainingSession();
    }
}
</script>
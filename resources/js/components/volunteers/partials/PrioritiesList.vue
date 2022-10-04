

<template>
    <div class="component-container">
        <div class="alert alert-info" v-if="!hasPriorities">
            <div v-if="volunteer.isBaseline()">
                {{ isPerson ? 'You are' : 'This volunteer is' }}
                currently a baseline volunteer.  If {{ isPerson ? 'You wish' : 'the volunteer wishes' }}
                to become a comprehensive volunteer please <a :href="'/app-user/'+this.volunteer.id+'/survey/priorities1/new'">set priorities</a>
                to start that process.
            </div>
            <div v-else>
                You haven't set any priorities.
            </div>
            <div class="mt-2">
                <a :href="'/app-user/'+this.volunteer.id+'/survey/priorities1/new'" class="btn btn-primary">Set Priorities</a>
            </div>
        </div>
        <div v-else>
            <div class="alert alert-danger my-4 col-10 mx-auto" v-if="!hasPriorities">
                <h4><strong>This volunteer did not complete an application.</strong></h4>
                Based on the expected workflow this is not possible, but there are a few ways it could have happened:
                <ul>
                    <li>This is a <strong>test database</strong> and you are looking at a volunteer that was created for testing purposes w/o completing an applications survey.</li>
                    <li>An admin <strong>created</strong> a volunteer user <strong>using the admin panel</strong>.</li>
                    <li>Something mysterious is going on and you should contact an administrator.</li>
                </ul>
            </div>
            <table class="table table-striped table-sm" v-else>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Curation Activity</th>
                        <th>Curation Group</th>
                        <th>Effort Experience</th>
                        <th>Activity Experience</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(priority, idx) in volunteer.latest_priorities" :key="idx">
                        <td>{{priority.priority_order}}</td>
                        <td>{{priority.curation_activity.name}}</td>
                        <td>{{(priority.curation_group) ? priority.curation_group.name : '--'}}</td>
                        <td>
                            {{priority.effort_experience | boolToHuman }}
                            <span v-if="priority.effort_experience == 1">
                                - {{priority.effort_experience_details}}
                            </span>
                        </td>
                        <td>
                            {{priority.activity_experience | boolToHuman }}
                            <span v-if="priority.activity_experience == 1">
                                - {{priority.activity_experience_details}}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="my-2 pb-2 border-bottom">
                Willing to volunteer outside of preferences: <strong>{{volunteer.latest_priorities[0].outside_panel}}</strong>
            </div>
            <div class="d-flex justify-content-between pt-2 align-items-center" v-if="!disableSetNew && !disableViewComplete">
                <a :href="prioritiesSurveyLink" class="btn btn-primary">
                    Set New Priorities
                </a>
            </div>
            <div v-if="volunteer.isBaseline()">
            </div>
        </div>
        <div v-if="volunteer.isBaseline()">
            <div v-if="user.isAdminOrProgrammer()">
                <button class="btn btn-light btn-sm" @click="confirmMakeComprehensive = true">
                    Make comprehensive
                </button>
            </div>
        </div>
        <comprehensive-convert-confirmation
            :volunteer="volunteer"
            v-model="confirmMakeComprehensive"
            @saved="$emit('saved')"
        />
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import ComprehensiveConvertConfirmation from '../ComprehensiveConvertConfirmation.vue'

    export default {
        components: {
            ComprehensiveConvertConfirmation
        },
        props: {
            volunteer: {
                required: true,
                type: Object
            },
            disableSetNew: {
                type: Boolean,
                default: false
            },
            disableViewComplete: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                outsidePanelOptions: {
                    1: 'Yes',
                    0: 'No',
                    2: 'Maybe'
                },
                confirmMakeComprehensive: false
            }
        },
        computed: {
            ...mapGetters({user: 'getUser'}),
            outsidePanel: function () {
                if (!this.hasPriorities) {
                    return 'no priorities'
                }
                return this.outsidePanelOptions[this.volunteer.latest_priorities[0].outside_panel]
            },
            prioritiesSurveyLink: function () {
                return '/app-user/'+this.volunteer.id+'/survey/priorities1/new'
            },
            hasPriorities: function() {
                return this.volunteer.priorities.length > 0
            },
            responseLink: function () {
                if (!this.hasPriorities || this.volunteer.latest_priorities[0].survey_id === null) {
                    return null
                }
                return '/surveys-by-id/'+this.volunteer.latest_priorities[0].survey_id+'/responses/'+this.volunteer.latest_priorities[0].response_id
            },
            isPerson () {
                return this.$store.state.user.isVolunteer() || this.$store.state.user.isCurrentUser(this.volunteer)
            }
        },
    }
</script>

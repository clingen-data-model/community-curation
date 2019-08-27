<style></style>

<template>
    <div>
        <h3>Assign Activity and Expert Panel</h3>
        <hr>
        <div>
            <h5>Volunteer Priorities</h5>
            <table class='table table-sm table-striped table-bordered'>
                <thead>
                    <tr>
                        <th colspan="2">Activity</th>
                        <th>Expert Panel</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(priority, i) in priorities" :key="i">
                        <td style="width: 5%;">{{i+1}}</td>
                        <td style="width: 25%">{{priority.activity}}</td>
                        <td>{{priority.expertPanel}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <h5>Assignments</h5>
            <button 
                @click="addingCurationActivity = true"
                class="btn btn-default form-control btn-primary" 
                v-if="activityCurationAssignments.length == 0 && !addingCurationActivity"
            >
                Assign Volunteer to a Curation Activity
            </button>
            <table class="table table-sm table-bordered" v-if="activityCurationAssignments.length > 0 || addingCurationActivity">
                <thead>
                    <tr>
                        <th style="width:30%">Curation Activities:</th>
                        <th>Expert Panels:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="assignment in activityCurationAssignments" :key="assignment.curationActivity.id">
                        <td>
                            {{assignment.curationActivity.assignable.name}}
                        </td>
                        <td>
                            <expert-panel-cell 
                                :assignment="assignment" 
                                :expert-panels="getExpertPanelsForCurationActivity(assignment.curationActivity.assignable.id)"
                                v-on:save="saveNewExpertPanel"
                            ></expert-panel-cell>
                        </td>
                    </tr>
                    <tr v-if="addingCurationActivity">
                        <td>
                            <select v-model="newCurationActivity" class="form-control form-control-sm">
                                <option :value="null">Select&hellip;</option>
                                <option v-for="(activity, idx) in unassignedCurationActivities" :key="idx" :value="activity">
                                    {{activity.name}}
                                </option>
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary" @click="saveNewCurationActivity">Save</button>
                            <button class="btn btn-sm btn-default" @click="cancelAddingActivity">Cancel</button>
                        </td>
                    </tr>
                    <tr v-if="!addingCurationActivity" class="border-top pt-2">
                    <td colspan="2">
                        <button class="btn btn-default border btn-sm" @click="addingCurationActivity = true">Add Curation Activity</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import createAssignment from '../../resources/assignments/create_assignment'
    import getAllCurationActivities from '../../resources/curation_activities/get_all_curation_activities'
    import getAllExpertPanels from '../../resources/expert_panels/get_all_expert_panels'
    import ExpertPanelCell from './ExpertPanelCell'

    export default {
        props: {
            volunteer: {
                required: true
            }
        },
        components: {
            ExpertPanelCell
        },
        data() {
            return {
                priorities: [
                    {
                        activity: 'Gene',
                        expertPanel: 'test EP'
                    },
                    {
                        activity: 'Dosage',
                        expertPanel: 'test EP'
                    }
                ],
                addingCurationActivity: false,
                newCurationActivity: null,
                newExpertPanelId: null,
                curationActivities: [],
                expertPanels: []
            }
        },
        computed: {
            activityCurationAssignments: function () {
                // return [];
                return this.volunteer.assignments
            },
            unassignedCurationActivities: function () {
                const actAssIds = this.activityCurationAssignments.map(assignment => assignment.curationActivity.assignable_id);
                return this.curationActivities
                    .filter((activity) => {
                        return actAssIds.indexOf(activity.id) == -1
                    })
            },
            unassignedExpertPanels: function () {
                const assignedPanels = Object.values(this.activityCurationAssignments.map(ac => ac.expertPanels)).flat().map(epAss => epAss.assignable);
                return this.expertPanels.filter(ep => assignedPanels.map(ep => ep.id).indexOf(ep.id) == -1)  
            }
        },
        methods: {
            fetchCurationActivities: async function()
            {
                this.curationActivities = await getAllCurationActivities();
            },
            fetchExpertPanels: async function ()
            {
                this.expertPanels = await getAllExpertPanels();
            },
            cancelAddingActivity() {
                this.newCurationActivity = null;
                this.addingCurationActivity = false;
            },
            getExpertPanelsForCurationActivity(curationActivityId) {
                return this.unassignedExpertPanels.filter(panel => panel.curation_activity_id == curationActivityId)    
            },
            saveNewCurationActivity() {
                createAssignment({
                    assignable_type: 'App\\CurationActivity',
                    assignable_id: this.newCurationActivity.id,
                    user_id: this.volunteer.id
                })
                .then(response => {
                    this.cancelAddingActivity();
                })
                console.log(this.newCurationActivity);
            },
            saveNewExpertPanel(expertPanel) {
                createAssignment({
                    assignable_type: 'App\\ExpertPanel',
                    assignable_id: expertPanel.id,
                    user_id: this.volunteer.id
                })
                .then(response => {
                    this.cancelAddingActivity();
                })
                console.log(this.expertPanel);
            }
        },
        mounted() {
            this.fetchCurationActivities();
            this.fetchExpertPanels();
        }
    
}
</script>
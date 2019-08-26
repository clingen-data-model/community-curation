<style></style>

<template>
    <div>
        <h3>Assign Activity and Expert Panel</h3>
        <hr>
        <h5>Volunteer Priorities</h5>
        <table class='table table-sm table-striped'>
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
        <hr>
        <h5>Assignments</h5>
        <button 
            @click="addingCurationActivity = true"
            class="btn btn-default form-control btn-primary" 
            v-if="activityCurationAssignments.length == 0 && !addingCurationActivity"
        >
            Assign Volunteer to a Curation Activity
        </button>
        <table class="table table-sm" v-if="activityCurationAssignments.length > 0 || addingCurationActivity">
            <thead>
                <tr>
                    <th style="width:30%">Curation Activities:</th>
                    <th>Expert Panels:</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="assignment in activityCurationAssignments" :key="assignment.activity.id">
                    <td>
                        {{assignment.activity.name}}
                    </td>
                    <td>
                        <expert-panel-cell 
                            :assignment="assignment" 
                            :expert-panels="getExpertPanelsForCurationActivity(assignment.activity.id)"
                            v-on:save="saveNewExpertPanel"
                            v-on:cancel=""
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
                return [
                    {
                        'activity': {
                            id: 3,
                            name: 'Gene',
                        },
                        'expertPanels': [
                            {
                                id: 1, 
                                name: 'test 1 EP',
                            },
                            {
                                id: 2, 
                                name: 'test 2 EP'
                            },
                        ],
                        needsAptitude: false,                    
                    },
                    { 
                        'activity': {
                            id: 2,
                            name: 'Dosage'
                        },
                        expertPanels: [],
                        needsAptitude: true,
                    },
                    {
                        activity: {
                            id: 1,
                            name: 'Actionability'
                        },
                        needsAptitude: false,
                        expertPanels: []
                    }
                ]
            },
            unassignedCurationActivities: function () {
                return this.curationActivities.filter(activity => this.activityCurationAssignments.map(assignment => assignment.activity.id).indexOf(activity.id) == -1)
            },
            unassignedExpertPanels: function () {
                const assignedPanels = Object.values(this.activityCurationAssignments.map(ac => ac.expertPanels)).flat(); 
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
                console.log(expertPanel);
            }
        },
        mounted() {
            this.fetchCurationActivities();
            this.fetchExpertPanels();
        }
    
}
</script>
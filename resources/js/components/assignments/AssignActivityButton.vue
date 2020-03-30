<template>
    <div class="d-inline-block">
        <button 
            class="btn btn-default border btn-sm" 
            @click="addingCurationActivity = true"
            :disabled="volunteer.volunteer_status_id == $store.state.configs.volunteers.statuses.retired"
            v-if="!addingCurationActivity"
        >
            Add Curation Activity
        </button>
        <div v-if="addingCurationActivity" class="form-inline">
            <select v-model="newCurationActivity" class="form-control form-control-sm">
                <option :value="null">Select&hellip;</option>
                <option v-for="(activity, idx) in unassignedCurationActivities" :key="idx" :value="activity">
                    {{activity.name}}
                </option>
            </select>
            &nbsp;
            <button class="btn btn-sm btn-primary" @click="saveNewCurationActivity">Save</button>
            <button class="btn btn-sm btn-default" @click="cancelAddingActivity">Cancel</button>
        </div>
    </div>
</template>
<script>
    import getAllCurationActivities from '../../resources/curation_activities/get_all_curation_activities'

    export default {
        props: {
            volunteer: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                newCurationActivity: null,
                addingCurationActivity: false,            
            }
        },
        computed: {
            activityCurationAssignments: function () {
                // return [];
                return this.volunteer.assignments
            },
            unassignedCurationActivities: function () {
                const actAssIds = this.activityCurationAssignments.map(assignment => assignment.assignable_id);
                return this.curationActivities
                    .filter((activity) => {
                        return actAssIds.indexOf(activity.id) == -1
                    })
            }
        },
        methods: {
            fetchCurationActivities: async function()
            {
                this.curationActivities = await getAllCurationActivities();
            },
            saveNewCurationActivity() {
                this.$emit('activity_selected', this.newCurationActivity);
            },
            cancelAddingActivity() {
                this.newCurationActivity = null;
                this.addingCurationActivity = false;
            },
        },
        created() {
            this.fetchCurationActivities();
        }        
    }
</script>
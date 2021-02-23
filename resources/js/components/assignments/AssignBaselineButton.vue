<template>
    <div>
        <button
            v-if="!volunteer.assignedToBaseline()"
            class="btn btn-sm border" 
            @click="assignBaselineActivity"
        >
            Assign to baseline
        </button>
    </div>
</template>
<script>
    import getBaselineActivities from '../../resources/curation_activities/getBaselineActivities'

    export default {
        props: {
            volunteer: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                baselineActivities: null
            }
        },
        computed: {

        },
        methods: {
            assignBaselineActivity() {
                this.$emit('assigned', this.baselineActivities[0]);
            },
            assignGeneticEvidenceTraining() {
                this.$emit('giveGeneticEvidence');
            },
            fetchBaselineActivities: async function() {
                this.baselineActivities = await getBaselineActivities();
                
            }
        },
        created() {
            this.fetchBaselineActivities();
        }
    }
</script>
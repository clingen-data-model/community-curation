<template>
    <select 
        v-model="newAssignmentStatusId"
        class="form-control form-control-sm"
        @change="$emit('assignmentstatuschange', newAssignmentStatusId)"
    >
        <option :value="null">Select</option>
        <option 
            v-for="assignmentStatus in assignmentStatuses"
            :key="assignmentStatus.id"
            :value="assignmentStatus.id"
        >
            {{assignmentStatus.name}}
        </option>
    </select>
</template>

<script>
    import getAllAssignmentStatuses from '../../../resources/assignments/get_all_assignment_statuses'

    export default {
        components: {},
        props: {
            assignment: {
                required: true,
                type: Object
            },
        },
        data() {
            return {
                newAssignmentStatusId: null,
                assignmentStatuses: []
            }
        },
        watch: {
            assignment: function (to, from) {
                this.newAssignmentStatusId = this.assignment.assignment_status_id
            }
        },
        methods: {
            fetchAssignmentStatuses: async function () {
                this.assignmentStatuses = await getAllAssignmentStatuses();
            },
        },
        mounted() {
            this.newAssignmentStatusId = this.assignment.assignment_status_id
            this.fetchAssignmentStatuses();
        }
    
}
</script>
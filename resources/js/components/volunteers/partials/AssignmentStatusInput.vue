<template>
    <span>
        <select 
            v-model="newAssignmentStatusId"
            class="form-control form-control-sm"
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
    </span>
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
                // newAssignmentStatusId: this.assignment.status.id,
                assignmentStatuses: []
            }
        },
        computed: {
            newAssignmentStatusId: {
                get() {
                    return this.assignment.status.id
                },
                set(value) {
                    this.$emit('assignmentstatuschange', value);
                }
            }
        },
        methods: {
            fetchAssignmentStatuses: async function () {
                this.assignmentStatuses = await getAllAssignmentStatuses();
            },
        },
        mounted() {
            this.fetchAssignmentStatuses();
        }
    
}
</script>
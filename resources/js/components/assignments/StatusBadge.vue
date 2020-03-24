<template>
    <span class="d-inline-block" style="width: 100px">
        <div class="badge badge-light border" :id="`assignment-status-badge-${assignment.id}`">
            {{assignment.status.name}}
            <span class="text-muted">|</span>
            <i class="material-icons text-muted" style="font-size: 1em">edit</i>
        </div>

        <b-popover
          :target="`assignment-status-badge-${assignment.id}`"
          triggers="hover focus"
          ref="statusPopover"
        >
            <small>Set Status:</small>

            <assignment-status-input
                :assignment="assignment"
                v-on:assignmentstatuschange="handleAssignmentChange"
                v-if="!savingUpdate"
            >
            </assignment-status-input>
            <div><small v-if="savingUpdate" class="text-muted">Saving status update...</small></div>
        </b-popover>

    </span>
</template>
<script>
import AssignmentStatusInput from '../volunteers/partials/AssignmentStatusInput';
import updateAssignment from '../../resources/assignments/update_assignment'

export default {
    components: {
        AssignmentStatusInput
    },
    props: {
        variant: {
            type: String,
            default: 'primary'
        },
        assignment: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            savingUpdate: false
        }
    },
    computed: {

    },
    methods: {
        handleAssignmentChange(newStatusId) {
            this.savingUpdate = true;
            updateAssignment(
                this.assignment.id, 
                {
                    'assignment_status_id': newStatusId
                }
            ).then( (response) => {
                this.$emit('assignmentsupdated')
                return response
            })
            .catch( error => console.error(error))
            .then( () => {
                this.savingUpdate = false;
                this.$refs.statusPopover.$emit('close')
            });
        },
    }
}
</script>
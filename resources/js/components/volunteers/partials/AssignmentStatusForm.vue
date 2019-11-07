<style></style>

<template>
    <div class="component-container">
        <h3>Update Assignment Statuses</h3>
        <hr>

        <h5>Curation Activity</h5>
        <assignment-status-input
            :assignment="assignment.curationActivity"
            v-on:assignmentstatuschange="updateActivityAssignmentStatus"
        ></assignment-status-input>
        <div
            class="mt-2"
            v-if="assignment.expertPanels.length > 0"
        >
            <h5>Expert Panels</h5>
            <div 
                v-for="panelAssignment in assignment.expertPanels"
                :key="panelAssignment.id"
                class="ml-3"
            >
                <assignment-status-input
                    :assignment="panelAssignment"
                    v-on:assignmentstatuschange="updatePanelAssignmentStatus($event, panelAssignment)"
                ></assignment-status-input>
            </div>
        </div>
    </div>
</template>

<script>
    import updateAssignment from '../../../resources/assignments/update_assignment'
    import AssignmentStatusInput from './AssignmentStatusInput'
import { Promise } from 'q';

    export default {
        components: {
            AssignmentStatusInput
        },
        props: {
            assignment: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                newActivityStatus: null,
                newPanelStatuses: {}
            }
        },
        methods: {
            updateActivityAssignmentStatus(newStatusId)
            {
                let promises = [];
                promises.push(
                    updateAssignment(
                        this.assignment.curationActivity.id, 
                        {
                            'assignment_status_id': newStatusId
                        }
                    )
                );
                if (this.assignment.expertPanels.length > 0 && newStatusId == 2) {
                    for (let idx in this.assignment.expertPanels) {
                        let panelAssignment = this.assignment.expertPanels[idx]
                        promises.push(
                            updateAssignment(
                                panelAssignment.id, 
                                {
                                    'assignment_status_id': newStatusId
                                }
                            )
                        )
                    }
                }
                Promise.all(promises)
                    .then(() => {
                        this.$emit('assignmentsupdated')
                    })
            },
            updatePanelAssignmentStatus(newStatusId, panelAssignment)
            {
                updateAssignment(
                    panelAssignment.id, 
                    {
                        'assignment_status_id': newStatusId
                    }
                ).then( () => this.$emit('assignmentsupdated'))
            },
        },
        mounted() {
            // this.newActivityStatus = this.assignment.assignment_status_id;
            // for (let idx in this.assignment.expertPanels) {
            //     let panelAssignment = this.assignment.expertPanels[idx]
            //     this.newPanelStatuses[panelAssignment.id] = panelAssignment.assignment_status_id
            // }
        }
    
}
</script>
<style></style>

<template>
    <div class="component-container">
        
        <div v-if="assignment.needsAptitude">
            <div v-if="assignment.needsAptitude">
                <div v-if="assignment.training.completed_at == null">
                    <div class="form-inline">
                        <div v-if="$store.state.user.notVolunteer()">
                            <div v-show="setTrainingDate" class="form-inline">
                                <label>Date completed:</label>
                                &nbsp;
                                <date-field v-model="newTrainingCompletedDate" class="form-control form-control-sm"></date-field>
                                <button class="btn btn-sm btn-primary" @click="emitTrainingCompleted(assignment.training)">Save</button>
                            </div>
                            <button class="btn btn-sm btn-primary" v-show="!setTrainingDate" @click="setTrainingDate = true">Mark Training complete</button>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div v-if="assignment.attestation.signed_at == null">
                        <a 
                            :href="`/attestations/${assignment.attestation.id}/edit`" 
                            class="btn btn-sm btn-primary"
                        >Sign attestation</a>
                    </div>
                    <!-- awaiting attestation -->
                </div>
            </div>
        </div>

        <ul class="list-unstyled mb-0">
            <li v-for="(panel, i) in assignment.expertPanels" :key="i"
                :class="{'text-strike text-muted': (panel.assignment_status_id == 2)}"
            >
                {{panel.assignable.name}}
            </li>
        </ul>
        <button 
            v-if="!addingExpertPanel && !assignment.needsAptitude" 
            class="btn btn-sm btn-xs border" 
            @click="addingExpertPanel = true"
            :disabled="volunteer.volunteer_status_id == 3"
        >
            Add expert panel
        </button>

        <div v-if="addingExpertPanel" class="form-inline">
            <select v-model="newExpertPanel" class="form-control form-control-sm">
                <option :value="null">Select&hellip;</option>
                <option v-for="(panel, idx) in expertPanels" :key="idx" :value="panel">
                    {{panel.name}}
                </option>
            </select>
            &nbsp;
            <button class="btn btn-sm btn-primary" @click="emitSave">Save</button>
            <button class="btn btn-sm btn-default" @click="cancelAddingExpertPanel">Cancel</button>
        </div>

    </div>
</template>

<script>
    import DateField from '../DateField'

    export default {
        components: {
            DateField
        },
        props: {
            volunteer: {
                required: true,
                type: Object
            },
            assignment: {
                required: true
            },
            expertPanels: {
                required: true,
                type: Array
            }
        },
        data() {
            return {
                newExpertPanel: null,
                addingExpertPanel: false,
                newTrainingCompletedDate: null,
                setTrainingDate: false
            }
        },
        methods: {
            emitSave() {
                this.$emit('save', this.newExpertPanel)
                this.addingExpertPanel = false;
                this.newExpertPanel = null
            },
            cancelAddingExpertPanel() {
                this.newExpertPanel = null
                this.addingExpertPanel = false;
                this.$emit('cancel');
            },
            emitTrainingCompleted(training) {
                this.$emit('trainingcompleted', {
                    id: training.id,
                    completed_at: this.newTrainingCompletedDate
                })
            }
        }
    
}
</script>
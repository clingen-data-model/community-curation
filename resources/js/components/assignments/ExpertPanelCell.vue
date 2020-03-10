<style></style>

<template>
    <div class="component-container">
        <ul class="list-unstyled mb-0">
            <li v-for="(panel, i) in assignment.subAssignments" :key="i"
                :class="{'text-strike text-muted': (panel.assignment_status_id == $store.state.configs.project.assignmentStatuses.retired)}"
            >
                {{panel.assignable.name}}
            </li>
        </ul>
        <button 
            v-if="!addingExpertPanel && !assignment.needsAptitude" 
            class="btn btn-sm btn-xs border" 
            @click="addingExpertPanel = true"
            :disabled="volunteer.volunteer_status_id == $store.state.configs.volunteers.statuses.retired"
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
        }
    
}
</script>
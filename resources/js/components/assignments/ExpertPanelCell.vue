<style>
</style>

<template>
    <div class="component-container">
        <table class="table table-borderless table-sm mb-1">
            <tr v-for="(panel, i) in assignment.subAssignments" :key="i">
                <td style="width: 60%">
                    <div>{{panel.assignable.name}}</div>
                </td>
                <td>
                    <status-badge :assignment="panel"
                    @assignmentsupdated="$emit('assignmentsupdated')"
                    ></status-badge>
                </td>
            </tr>
        </table>
        <button 
            v-if="showAddButton" 
            class="btn btn-sm btn-xs border" 
            @click="addingExpertPanel = true"
            :disabled="volunteer.volunteer_status_id == $store.state.configs.volunteers.statuses.retired"
        >
            Add expert panel
        </button>
        <small class="text-muted btn border btn-xs" v-if="!hasMoreExpertPanels">All panels assigned</small>

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
    import StatusBadge from './StatusBadge'

    export default {
        components: {
            DateField,
            StatusBadge
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
        computed: {
            showAddButton: function () {
                return !this.addingExpertPanel 
                        && !this.assignment.needsAptitude
                        && this.hasMoreExpertPanels;
            },
            hasMoreExpertPanels: function () {
                return this.expertPanels.length > 0;
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
            }
        }
    
}
</script>
<template>
    <div class="component-container">
        <table class="table table-borderless table-sm mb-1">
            <tr v-for="(subAss, i) in assignment.sub_assignments" :key="i">
                <td style="width: 60%">
                    <div>{{subAss.assignable.name}}</div>
                </td>
                <td>
                    <status-badge :assignment="subAss"
                    @assignmentsupdated="$emit('assignmentsupdated')"
                    ></status-badge>
                </td>
                <td class="text-right">
                    <delete-button @click="removeAssignment(subAss)"></delete-button>
                </td>
            </tr>
        </table>
        <button 
            v-if="showAddButton" 
            class="btn btn-sm btn-xs border" 
            @click="addingCurationGroup = true"
            :disabled="volunteer.volunteer_status_id == $store.state.configs.volunteers.statuses.retired"
        >
            Add curation group
        </button>
        <small class="text-muted btn border btn-xs" v-if="!hasMoreCurationGroups">All panels assigned</small>

        <div v-if="addingCurationGroup" class="form-inline">
            <select v-model="newCurationGroup" class="form-control form-control-sm">
                <option :value="null">Select&hellip;</option>
                <option v-for="(panel, idx) in curationGroups" :key="idx" :value="panel">
                    {{panel.name}}
                </option>
            </select>
            &nbsp;
            <button class="btn btn-sm btn-primary" @click="emitSave">Save</button>
            <button class="btn btn-sm btn-default" @click="cancelAddingCurationGroup">Cancel</button>
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
            curationGroups: {
                required: true,
                type: Array
            }
        },
        data() {
            return {
                newCurationGroup: null,
                addingCurationGroup: false,
            }
        },
        computed: {
            showAddButton: function () {
                return !this.addingCurationGroup 
                        && !this.assignment.needsAptitude
                        && this.hasMoreCurationGroups;
            },
            hasMoreCurationGroups: function () {
                return this.curationGroups.length > 0;
            }
        },
        methods: {
            emitSave() {
                this.$emit('save', this.newCurationGroup)
                this.addingCurationGroup = false;
                this.newCurationGroup = null
            },
            cancelAddingCurationGroup() {
                this.newCurationGroup = null
                this.addingCurationGroup = false;
                this.$emit('cancel');
            },
            removeAssignment(subAss) {
                if (confirm("You are about to unassign "+this.volunteer.name+" from "+subAss.assignable.name+". \n\nThis cannot be undone.")) {
                    this.$emit('unassign', subAss);
                }
            }
        }
    
}
</script>
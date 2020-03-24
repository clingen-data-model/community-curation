<template>
    <div>
        <table class="table table-sm table-borderless mb-1">
            <tr>
                <th class="border-bottom" style="width: 50%"><small>Gene Symbol</small></th>
                <th class="border-bottom"><small>Status</small></th>
                <th class="border-bottom"><small>Protocol Link</small></th>
            </tr>
            <tr v-for="(subAss, i) in assignment.subAssignments" :key="i">
                <td>
                    {{subAss.assignable.symbol}}
                    <small class="text-muted">({{subAss.assignable.hgnc_id}})</small>
                </td>
                <td>
                    <status-badge :assignment="subAss"
                        @assignmentsupdated="$emit('assignmentsupdated')"
                    ></status-badge>
                </td>
                <td>
                    <a :href="'/storage/'+subAss.assignable.protocol_path">Protocol</a>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <button 
                        v-if="!addingGene && !assignment.needsAptitude" 
                        class="btn btn-sm btn-xs border" 
                        @click="addingGene = true"
                        :disabled="volunteer.volunteer_status_id == $store.state.configs.volunteers.statuses.retired"
                    >
                        Add Gene
                    </button>

                    <div v-if="addingGene" class="form-inline">
                        <select 
                            v-model="newGene" 
                            class="form-control form-control-sm"
                            :disabled="loadingGenes"
                        >
                            <option :value="null">Select&hellip;</option>
                            <option v-for="(gene, idx) in filteredGenes" :key="idx" :value="gene">
                                {{gene.symbol}} - {{gene.hgnc_id}}
                            </option>
                        </select>
                        &nbsp;
                        <button class="btn btn-sm btn-primary" @click="emitSave">Save</button>
                        <button class="btn btn-sm btn-default" @click="cancelAddingGene">Cancel</button>
                    </div>
                </td>
            </tr>
        </table>
        <!-- <ul class="list-unstyled mb-0">
            <li v-for="(subAss, i) in assignment.subAssignments" :key="i"
                :class="{'text-strike text-muted': (subAss.assignment_status_id == $store.state.configs.project.assignmentStatuses.retired)}"
                class="d-flex justify-content-between"
            >
                <div>
                    {{subAss.assignable.symbol}} 
                    <small class="text-muted">
                        ({{subAss.assignable.hgnc_id}})
                    </small>
                    <status-badge :assignment="subAss"
                        @assignmentsupdated="$emit('assignmentsupdated')"
                    ></status-badge>
                </div>
                <div v-if="subAss.assignable.protocol_path" class="mr-3">
                    <a :href="'/storage/'+subAss.assignable.protocol_path">Protocol</a>
                </div>
            </li>
        </ul> -->
    </div>
</template>
<script>
import getAllGenes from '../../resources/genes/get_all_genes'
import StatusBadge from './StatusBadge'

export default {
    components: {
        StatusBadge
    },
    props: {
        assignment: {
            required: true
        },
        volunteer: {
            required: true
        }
    },
    data() {
        return {
            loadingGenes: false,
            newGene: null,
            addingGene: false,
            genes: []
        }
    },
    computed: {
        filteredGenes: function () {
            return this.genes.filter(gene => {
                return !this.assignment.subAssignments.map(subAss => subAss.assignable.id).includes(gene.id)
            });
        }
    },
    methods: {
        emitSave() {
            this.$emit('save', this.newGene)
            this.addingGene = false;
            this.newGene = null
        },
        cancelAddingGene() {
            this.newGene = null
            this.addingGene = false;
            this.$emit('cancel');
        },
        async getGenes() {
            this.genes = await getAllGenes();    
            this.loadingGenes = true;
            this.loadingGenes = false;
        }
    },
    mounted() {
        this.getGenes();
    }
}
</script>
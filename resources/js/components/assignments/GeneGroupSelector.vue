<template>
    <div>
        <table class="table table-sm table-borderless mb-1">
            <tr>
                <th class="border-bottom" style="width: 50%"><small>Genes</small></th>
                <th class="border-bottom"><small>Status</small></th>
                <th class="border-bottom" colspan="2"><small>Links</small></th>
            </tr>
            <tr v-for="(subAss, i) in assignment.sub_assignments" :key="i">
                <td>
                    {{subAss.assignable.symbol}}
                </td>
                <td>
                    <status-badge :assignment="subAss"
                        @assignmentsupdated="$emit('assignmentsupdated')"
                    ></status-badge>
                </td>
                <td>
                    <small>
                        <a :href="`/genes/${subAss.assignable.symbol}/protocol`" v-if="subAss.assignable.protocol_path">
                            Protocol
                        </a>
                        <br>
                        <a :href="'/storage/'+subAss.assignable.hypothesis_group_url" v-if="subAss.assignable.hypothesis_group_url">
                            Hypothes.is
                        </a>

                    </small>
                </td>
                <td class="text-right">
                    <delete-button @click="removeAssignment(subAss)"></delete-button>
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
                                {{gene.symbol}}
                            </option>
                        </select>
                        &nbsp;
                        <button class="btn btn-sm btn-primary" @click="emitSave">Save</button>
                        <button class="btn btn-sm btn-default" @click="cancelAddingGene">Cancel</button>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</template>
<script>
import getAllGenes from '../../resources/genes/get_all_genes'
import StatusBadge from './StatusBadge.vue'

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
            genes: [],
        }
    },
    computed: {
        filteredGenes: function () {
            return this.genes.filter(gene => {
                return !this.assignment.sub_assignments.map(subAss => subAss.assignable.id).includes(gene.id)
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
        },
        removeAssignment(panelAssignment) {
            if (confirm("You are about to unassign "+this.volunteer.name+" from "+panelAssignment.assignable.name+". \n\nThis cannot be undone.")) {
                this.$emit('unassign', panelAssignment);
            }
        }
    },
    mounted() {
        this.getGenes();
    }
}
</script>
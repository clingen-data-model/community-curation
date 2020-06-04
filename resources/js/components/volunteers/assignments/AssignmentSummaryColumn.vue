<template>
    <div>
        <div
            v-if="asn.hasSubAssignments()"
        >
            <ul 
                class="list-unstyled"
            >
                <li v-for="subAsn in asn.sub_assignments" :key="subAsn.id">
                    <small>
                        {{subAsn.assignable.name}}
                        <div class="float-right">
                            <a 
                                v-if="subAsn.assignable.protocol_path"
                                :href="`/genes/${subAsn.assignable.symbol}/protocol`"
                                class="float-right"
                            >
                                Protocol
                            </a>
                            <br>
                            <a v-if="subAsn.assignable.hypothesis_group"
                                :href="subAsn.assignable.hypothesis_group_url"
                                target="hypothes.is"
                                class="hypothesis-link"
                            >
                                Hypothes.is
                            </a>

                        </div>
                    </small>
                </li>
            </ul>
        </div>
        <div v-else>
            <div v-if="asn.user_aptitudes.untrained().length > 0">
                <small>
                    <strong>Pending Training:</strong>
                    <ul class="list-unstyled ml-2 mt-0">
                        <li v-for="trn in asn.user_aptitudes.untrained()" :key="trn.id">
                            {{trn.aptitude.name}}
                        </li>
                    </ul>
                </small>
            </div>
            <div v-else-if="asn.user_aptitudes.needsAttestation().length > 0">
                <small>
                    <ul class="list-unstyled ml-2 mt-0">
                        <li v-for="ua in asn.user_aptitudes.needsAttestation()" :key="ua.id">
                            <a :href="`/attestations/${ua.attestation_id}/edit`"
                                class="btn btn-xs btn-primary"
                            >
                                Sign Attestation
                            </a>
                        </li>
                    </ul>
                </small>
            </div>
            <div v-else>
                <only-volunteer>
                    <small>You will be assigned to a {{subAssignmentType}} shortly.</small>
                </only-volunteer>
                <non-volunteer>
                    <button 
                        @click="$emit('manageAssignments')"
                        class="btn btn-xs btn-primary"
                    >
                        Assign to {{subAssignmentType}}
                    </button>
                </non-volunteer>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        assignment: {
            required: true,
            type: Object
        }
    },
    computed: {
        asn: function () {
            return this.assignment;
        },
        subAssignmentType: function () {
            return this.assignment.assignable.curation_activity_type_id == 1 ? 'expert panel' : 'gene';
        }
    }
}
</script>
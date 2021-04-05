<template>
    <div>
        <table class="table table-striped">
            <tr>
                <th>Aptitude</th>
                <th colspan="2">Date Signed</th>
            </tr>
            <tr v-for="(attestation, idx) in volunteer.attestations" :key="idx">
                <td>{{attestation.aptitude.name}}</td>
                <td>
                    <span v-if="attestation.signed_at">{{attestation.signed_at | formatDate('YYYY-MM-DD')}}</span>
                    <span class="text-muted" v-else>unsigned</span>
                </td>
                <td style="width: 8rem; text-align:right">
                    <button class="btn btn-sm border" v-b-modal.attestation-data-modal @click="selectedAttestation = attestation">View data</button>
                </td>
            </tr>
            <tr v-if="volunteer.attestations.length == 0">
                <td colspan="2">
                    <div class="alert alert-secondary text-center">No attestations signed.</div>
                </td>
            </tr>
        </table>
        <b-modal id="attestation-data-modal" :title="selectedAttestation ? selectedAttestation.aptitude.name : ''">
            <pre v-if="selectedAttestation">{{selectedAttestation.data}}</pre>
        </b-modal>
    </div>
</template>
<script>
    export default {

        props: {
            volunteer: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                attestations: [],
                selectedAttestation: null
            }
        },
    }
</script>
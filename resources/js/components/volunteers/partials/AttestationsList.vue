<template>
    <div>
        <table class="table table-striped">
            <tr>
                <th>Aptitude</th>
                <th>Date Signed</th>
            </tr>
            <tr v-for="(attestation, idx) in volunteer.attestations" :key="idx">
                <td>{{attestation.aptitude.name}}</td>
                <td>
                    <span v-if="attestation.signed_at">{{attestation.signed_at | formatDate('YYYY-MM-DD')}}</span>
                    <span class="text-muted" v-else>unsigned</span>
                </td>
            </tr>
            <tr v-if="volunteer.attestations.length == 0">
                <td colspan="2">
                    <div class="alert alert-secondary text-center">No attestations signed.</div>
                </td>
            </tr>
        </table>
    </div>
</template>
<script>
    import getAllAttestations from '../../../resources/attestations/get_all_attestations';

    export default {

        props: {
            volunteer: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                attestations: []
            }
        },
    }
</script>
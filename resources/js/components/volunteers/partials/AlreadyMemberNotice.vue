<template>
    <user-can permission="see clingen member info" class="alert alert-info" v-if="volunteer.isClingenMember()">
        {{volunteer.name}} is a ClinGen member in the following curation groups: 
        <ul>
            <li v-for="cgName in volunteer.member_groups.map(i => i.name)" :key="cgName">
                {{cgName}}
            </li>
        </ul>
    </user-can>
</template>
<script>
import { mapGetters } from 'vuex';

export default {
    props: {
        volunteer: {
            required: true,
            type: Object
        }        
    },
    computed: {
        ...mapGetters({
            user: 'getUser'
        }),
    }
}
</script>
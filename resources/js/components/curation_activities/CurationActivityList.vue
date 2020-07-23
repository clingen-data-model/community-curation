<template>
    <b-table 
        :fields="fields"
        :items="curationActivities"
        @row-clicked="navigateToActivity"
    ></b-table>
</template>
<script>
export default {
    props: {
        initialActivities: {
            required: false,
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            curationActivities: this.initialActivities,
            fields: [
                {
                    key: 'id',
                    label: 'ID',
                    sortable: true
                },
                {
                    key: 'name',
                    label: 'Name',
                    sortable: true
                },
            ],
        }
    },
    methods: {
        navigateToActivity(activity) {
            window.location = '/curation-activities/'+activity.id
        }
    }
}
</script>
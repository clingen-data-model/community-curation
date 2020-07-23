<script>
import GroupDetail from '../GroupDetail';

export default {
    extends: GroupDetail,
    data() {
        return {
            groupType: 'Curation Activities',
            groupUrl: '/curation-activities/',
            additionalTableFields: [
                {
                    key: 'sub_assignments',
                    label: 'Curation groups',
                    formatter (value) {
                        // console.log(value);
                        return value.map(subAsn => subAsn.assignable.name).join(", ");
                    },
                    sortable: true
                }
            ]
        }
    },
    computed: {

    },
    methods: {

    }
}
</script>
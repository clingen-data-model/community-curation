
<script>
import GroupDetail from '../GroupDetail';

export default {
    extends: GroupDetail,
    data() {
        return {
            groupType: 'Curation Groups',
            groupUrl: '/curation-groups',
            adminBaseUrl: '/admin/curation-group',
            assignmentStatusPath: 'status.parent.name',
        }
    },
    computed: {
        volunteersByStatus() {
            return this.countVolunteersBy(assignment => {
                return assignment.parent.status ? assignment.parent.status.name : 'Unknown'
            });
        },
    }
}
</script>
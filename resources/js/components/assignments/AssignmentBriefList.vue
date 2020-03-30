<template>
    <ul class="list-unstyled">
        <li v-for="(ass, idx) in assignments" :key="idx">
            {{ass.assignable.name}}
            <small v-if="ass.sub_assignments.length > 0">
                -
                <span>{{ass.sub_assignments.map(p => p.assignable.name).join(", ")}}</span>
            </small>
            <small 
                v-else-if="!primaryAptitudeGranted(ass)" 
                class="text-muted"
            >
                - Needs aptitude
            </small>
            <small v-else class="text-muted">
                - None
            </small>                            
        </li>
    </ul>

</template>
<script>
export default {
    props: {
        assignments: {
            type: Array,
            default: []
        }
    },
    methods: {
        primaryAptitudeGranted (assignment) {
            return assignment.user_aptitudes.find(ua => ua.aptitude.is_primary == 1).granted_at;
        }
    }
}
</script>
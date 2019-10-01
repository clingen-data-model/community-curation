<style></style>

<template>
    <div class="component-container">
        <div class="alert alert-danger my-4 col-10 mx-auto" v-if="!volunteer.application">
            <h4><strong>This volunteer did not complete an application.</strong></h4>
            Based on the expected workflow this is not possible, but there are a few ways it could have happened:
            <ul>
                <li>This is a <strong>test database</strong> and you are looking at a volunteer that was created for testing purposes w/o completing an applications survey.</li>
                <li>An admin <strong>created</strong> a volunteer user <strong>using the admin panel</strong>.</li>
                <li>Something mysterious is going on and you should contact an administrator.</li>
            </ul>
        </div>

        <table class="table table-striped table-sm" v-else>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Curation Activity</th>
                    <th>Expert Panel</th>
                    <th>Effort Experience</th>
                    <th>Activity Experience</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(priority, idx) in volunteer.latest_priorities" :key="idx">
                    <td>{{priority.priority_order}}</td>
                    <td>{{priority.curation_activity.name}}</td>
                    <td>{{priority.expert_panel.name}}</td>
                    <td>
                        {{priority.effort_experience | boolToHuman }}
                        <span v-if="priority.effort_experience == 1">
                            - {{priority.effort_experience_details}}
                        </span>
                    </td>
                    <td>
                        {{priority.activity_experience | boolToHuman }}
                        <span v-if="priority.activity_experience == 1">
                            - {{priority.activity_experience_details}}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>        
    </div>
</template>

<script>
    export default {
        props: {
            volunteer: {
                required: true,
                type: Object
            }
        }
    }
</script>
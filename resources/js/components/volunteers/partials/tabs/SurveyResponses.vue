<template>
    <div>
        <b-tabs vertical pills>
            <b-tab title="Application">
                <survey-data :survey-data="volunteer.application">
                    <h4>
                        <strong>This volunteer did not complete an application.</strong>
                    </h4>
                    
                    Based on the expected workflow this is not possible, but there are a few ways it could have happened:
                    
                    <ul>
                        <li>This is a <strong>test database</strong> and you are looking at a volunteer that was created for testing purposes w/o completing an applications survey.</li>
                        <li>An admin <strong>created</strong> a volunteer user <strong>using the admin panel</strong>.</li>
                        <li>Something mysterious is going on and you should contact an administrator.</li>
                    </ul>
                </survey-data>
            </b-tab>
            <b-tab title="3 Month Followup">
                <survey-data :survey-data="volunteer.three_month">
                    <non-volunteer>The volunteer hasn't completed a 3 month followup yet</non-volunteer>
                    <only-volunteer>
                        No data yet.
                    </only-volunteer>
                </survey-data>
            </b-tab>
            <b-tab title="6 Month Followup">
                <survey-data :survey-data="volunteer.six_month">
                    <non-volunteer>The volunteer hasn't completed a 6 month followup yet</non-volunteer>
                    <only-volunteer>
                        No data yet.
                    </only-volunteer>
                </survey-data>
            </b-tab>
        </b-tabs>
    </div>
</template>
<script>
    import SurveyData from '../SurveyData'

    export default {
        components: {
            SurveyData,
        },
        props: {
            volunteer: {
                required: true,
                type: Object
            }
        },
        data() {
            return {
                
            }
        },
        computed: {

        },
        methods: {

        }
    }
</script>
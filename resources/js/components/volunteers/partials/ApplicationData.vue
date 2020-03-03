<style></style>

<template>
    <div class="component-container">
        <b-table
            :items="applicationData"
            small
            hover
            header-variant="light"
            :fields="fields"
            v-if="applicationData.length > 0"
        >
            <template v-slot:cell(variable)="{item}">
                <strong>{{item.variable}}</strong>
            </template>
            <template v-slot:cell(value)="{item}">
                <ul v-if="isArray(item.value)" class="list-unstyled">
                    <li v-for="(val, idx) in item.value" :key="idx">
                        {{val}}
                    </li>
                </ul>
                <div v-else>
                    {{item.value}}
                </div>
            </template>
        </b-table>
        <div v-else class="alert alert-danger my-4 col-10 mx-auto">
            <h4>
                <strong>This volunteer did not complete an application.</strong>
            </h4>
            
            Based on the expected workflow this is not possible, but there are a few ways it could have happened:
            
            <ul>
                <li>This is a <strong>test database</strong> and you are looking at a volunteer that was created for testing purposes w/o completing an applications survey.</li>
                <li>An admin <strong>created</strong> a volunteer user <strong>using the admin panel</strong>.</li>
                <li>Something mysterious is going on and you should contact an administrator.</li>
            </ul>
        </div>        
    </div>
</template>

<script>
    export default {
        props: {
            volunteer: {
                required: true,
                type: Object
            }
        },
        data() {
            return {
                fields: ['variable', 'value']
            }
        },
        computed: {
            applicationData: function () {
                if (!this.volunteer.application) {
                    return [];
                }
                const variableNames = Object.keys(this.volunteer.application);
                let data = [];
                for (let index = 0; index < variableNames.length; index++) {
                    const variable = variableNames[index];
                    if (['respondent_type', 'respondent_id', 'survey_id', 'duration', 'last_page'].indexOf(variable) > -1) {
                        continue;
                    }

                    let value = this.volunteer.application[variable];

                    data.push({
                        'variable': variable,
                        'value': value
                    })
                }
                return data;
            },
        },
        methods: {
            isArray(item) {
                return item instanceof Array;
            }
        }    
    }
</script>
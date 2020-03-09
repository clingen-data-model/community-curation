<template>
    <section>
        <b-table
            :items="attributes"
            small
            hover
            header-variant="light"
            :fields="fields"
            v-if="attributes.length > 0"
        >
            <template v-slot:cell(variable)="{item}">
                <strong>{{item.value.variable}}</strong>
            </template>
            <template v-slot:cell(value)="{item}">
                <ul v-if="isArray(item.value.value)" class="list-unstyled">
                    <li v-for="(val, idx) in item.value.value" :key="idx">
                        {{val}}
                    </li>
                </ul>
                <div v-else>
                    {{item.value.value}}
                </div>
            </template>
        </b-table>
        <div v-else class="alert alert-danger my-4 col-10 mx-auto">
            <slot></slot>
        </div>        
    </section>
</template>
<script>
export default {
    props: {
        surveyData: {
            required: true,
            type: Object|null
        }
    },
    data() {
        return {
            fields: ['variable', 'value'],
        }
    },
    computed: {
        attributes: function () {
            if (!this.surveyData) {
                return [];
            }
            const variableNames = Object.keys(this.surveyData);
            let data = [];
            for (let index = 0; index < variableNames.length; index++) {
                const variable = variableNames[index];
                if (['respondent_type', 'respondent_id', 'survey_id', 'duration', 'last_page'].indexOf(variable) > -1) {
                    continue;
                }

                let value = this.surveyData[variable];

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
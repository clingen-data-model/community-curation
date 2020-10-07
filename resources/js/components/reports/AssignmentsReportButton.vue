<template>
    <span>
        <a :href="assignmentReportUrl" class="btn btn-sm btn-primary"
            id="assignments-button"
        >
            Assignments Report
            <span v-if="hasFilters">*</span>
        </a>
        <b-popover target="assignments-button" triggers="hover" v-if="hasFilters">
            <template v-slot:title>
                <small>
                    The report will be filtered by:
                    <ul>
                        <li v-for="(value, name) in activeFilters" :key="name">
                            {{name | filterToLabel}}
                        </li>
                    </ul>
                </small>
            </template>
        </b-popover>
    </span>
</template>
<script>
import queryStringFromParams from '../../http/query_string_from_params'

export default {
    props: {
        filter: {
            type: Object,
            default: () =>{
                return {};
            }
        },
        sortBy: {
            type: String,
            default: 'last_name'
        },
        sortDesc: {
            type: Boolean,
            value: false
        }
    },
    data() {
        return {
            
        }
    },
    filters: {
        filterToLabel(filter) {
            if (filter == 'searchTerm') {
                return 'Search'
            }
            const label = filter.replace('_id', '').replace('_', ' ');
            return label.charAt(0).toUpperCase() + label.slice(1);
        }
    },
    computed: {
        assignmentReportUrl() {
            const baseUrl = '/assignments-report';
            let url = baseUrl+'?'+queryStringFromParams({...this.filter, ...{sortBy: this.sortBy, sortDesc: this.sortDesc}});
            console.log(url);
            return url;
        },
        hasFilters() {
            return Object.keys(this.filter).filter(key => this.filter[key] !== null && this.filter[key] != '').length > 0
        },
        activeFilters: function () {
            return Object.keys(this.filter)
                .filter(key => this.filter[key] !== null && this.filter[key] !== '')
                .reduce((obj, key) => {
                    obj[key] = this.filter[key];
                    return obj;
                }, {})
        },
    },
    methods: {

    }
}
</script>
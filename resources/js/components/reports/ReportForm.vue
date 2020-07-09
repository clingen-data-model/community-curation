<template>
    <div>
        <div class="form-group form-inline">
            <label for="report-type">Report type: </label>
            &nbsp;
            <select v-model="reportUrl" id="report-type" class="form-control form-control-sm">
                <option v-for="(type, url) in reportTypes" :value="url" :key="type">{{type}}</option>
            </select>
        </div>
        <volunteer-filters :hide-search="true" @filters-changed="handleFiltersChanged" ref="volunteerFilters">
            <template v-slot:before>
                <label for=""> Filters:</label>
            </template>
        </volunteer-filters>
        <div class="form-group form-inline" v-show="reportUrl == '/applications-report'">
            <label for="start-date">Date range:</label>
            &nbsp;
            <date-field v-model="startDate" class="form-control form-control-sm"></date-field>
            &nbsp;to&nbsp;
            <date-field v-model="endDate" class="form-control form-control-sm"></date-field>
        </div>
        <div class="form-group">
            <button class="btn btn-light border btn-sm" @click="resetForm">Reset</button>
            <button class="btn btn-primary btn-sm" @click="getReport" :disabled="reportUrl === null">Get report</button>
        </div>
    </div>
</template>
<script>
import VolunteerFilters from '../volunteers/VolunteerFilters'
import queryStringFromParams from '../../http/query_string_from_params'
import moment from 'moment'

export default {
    components: {
        VolunteerFilters
    },
    props: {
        
    },
    data() {
        return {
            reportTypes: [],
            reportUrl: null,
            currentFilters: null,
            startDate: null,
            endDate: null
        }
    },
    computed: {

    },
    methods: {
        getReportTypes () {
            this.reportTypes = {
                '/assignments-report': 'Assignment Report', 
                '/applications-report':'Application Report'
            }
        },
        handleFiltersChanged (filters) {
            this.currentFilters = filters
        },
        resetForm () {
            this.currentFilters = null;
            this.reportUrl = null;
            this.startDate = null;
            this.endDate = null;
            console.info('this.$refs.volunteerFilters', this.$refs.volunteerFilters);
            this.$refs.volunteerFilters.reset();
        },
        getReport () {
            let params = {...params, ...this.currentFilters};

            if (this.startDate) {
                params.start_date = moment(this.startDate).format('YYYY-MM-DD')
            }
            if (this.endDate) {
                params.end_date = moment(this.endDate).format('YYYY-MM-DD')
            }
            const url = this.reportUrl+'?'+queryStringFromParams(params);
            window.location = url;

            // this.resetForm();

        }
    },
    mounted() {
        this.getReportTypes();
    }
}
</script>
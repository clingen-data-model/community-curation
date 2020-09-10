<template>
    <div>
        <volunteer-filters @filters-changed="updateFilters" :hide-search="true">
            <div slot="before">
                <div class="form-inline pr-3 border-right">
                    <date-field v-model="startDate" class="form-control form-control-sm" placeholder="Start date" @input="updateFilters"/>
                    &nbsp;-&nbsp;
                    <date-field v-model="endDate" class="form-control form-control-sm" placeholder="End date" @input="updateFilters"/>
                </div>
            </div>
        </volunteer-filters>
        <div class="d-flex">
            <div v-for="(metric, index) in metrics" :key="index" class="mr-1 w-50">
                <h3 class="text-center">{{index|titleCase}}</h3>
                <pie-chart :chart-data="metric"></pie-chart>
            </div>
        </div>
    </div>
</template>
<script>
import PieChart from '../charts/PieChart'
import getMetrics from '../../resources/volunteers/get_metrics'
import VolunteerFilters from '../volunteers/VolunteerFilters'
import {omitBy, isUndefined} from 'lodash';
import moment from 'moment-timezone';

export default {
    components: {
        // 'volunteer-filters': () => import(/* volunteer-filters */ '../volunteers/VolunteerFilters')
        VolunteerFilters,
        PieChart
    },
    data() {
        return {
            metrics: {},
            startDate: null,
            endDate: null,
            volunteerFilters: {}
        }
    },
    filters: {
        titleCase(title) {
            if (typeof title != 'string') 
                return title;

            return title.replace(/id/, '')
                    .toLowerCase()
                    .split('_')
                    .map(word => word.charAt(0).toUpperCase()+word.slice(1))
                    .join(' ');
        }
    },
    methods: {
        async getMetrics() {
            const staticParams = {};

            console.log(this.volunteerFilters);
            let params = {...staticParams, ...this.volunteerFilters};
            console.info('this.startDate', this.startDate)
            if (this.startDate) {
                console.info('this.startDate', this.startDate)
                if (this.startDate instanceof moment) {
                    params.start_date = this.startDate.format('YYYY-MM-DD');
                } else {
                    params.start_date = moment(this.startDate).format('YYYY-MM-DD');
                }
            }
            if (this.endDate) {
                params.end_date = moment(this.endDate).format('YYYY-MM-DD');
            }
            params = omitBy(params, isUndefined);
            console.log(params);
            this.metrics = await getMetrics(params);
        },
        updateFilters(filters) {
            this.volunterFilters = {...this.volunterFilters, ...filters};
            this.getMetrics();
        }
    },
    mounted() {
        console.log()
        this.getMetrics();
    }
}
</script>
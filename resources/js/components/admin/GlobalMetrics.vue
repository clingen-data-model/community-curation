<style>
    .charts-container {
        min-height: 150px;
    }
    .pie-container {
        width: 225px;
    }
</style>
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
        <div class="d-relative chart-container mt-1">
            <div class="d-flex flex-wrap" v-if="!loading || hasMetrics">
                <div v-for="(metric, index) in metrics" :key="index" class="pie-container px-1">
                    <h4 class="text-center">{{index|titleCase}}</h4>
                    <pie-chart :chart-data="metric"></pie-chart>
                </div>
            </div>
            <div class="text-muted text-center p-1 w-100 h-100 p-absolute" v-if="loading">Loading...</div>
        </div>
    </div>
</template>
<script>
import getMetrics from '../../resources/volunteers/get_metrics'
import {omitBy, isUndefined} from 'lodash';
import moment from 'moment-timezone';

export default {
    data() {
        return {
            metrics: {},
            startDate: null,
            endDate: null,
            volunteerFilters: {},
            loading: false
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
    computed: {
        hasMetrics() {
            return Object.keys(this.metrics).length > 0;
        }
    },
    methods: {
        async getMetrics() {
            const staticParams = {};

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
            this.loading = true;
            this.metrics = await getMetrics(params);
            this.loading = false;
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
<template>
    <div class="chart pie-chart">
        <canvas ref="chartCanvas"></canvas>
    </div>
</template>

<script>
import Chart from 'chart.js'

export default {
    props: {
        chartData: {
            type: Object,
            required: true
        },
        options: {
            type: Object,
            default () {
                return {}
            }
        },
        title: {
            type: String
        }
    },
    data() {
        return {
            chart: null,
            colors: [],
            defaultConfig: {
                legend: {
                    position: 'left',
                    display: false
                }
            } 
        }
    },
    watch: {
        chartData: {
            deep: true,
            handler () {
                this.renderChart()
            }
        }
    },
    methods: {
        renderChart(context, options) {
            const data  = {
                type: 'pie',
                data: {
                    labels: Object.keys(this.chartData),
                    datasets: [{
                        label: 'test',
                        data: Object.values(this.chartData),
                        backgroundColor: (context) => {
                            if (this.colors.length < Object.keys(this.chartData).length) {
                                this.$set(this.colors, context.dataIndex, `rgba(${this.generateRandomColor(256)},${this.generateRandomColor(256)},${this.generateRandomColor(256)},0.4)`);
                            }
                            return this.colors[context.dataIndex]
                        }
                    }]
                },
                options: {...this.defaultConfig, ...this.options}
            }

            this.chart = new Chart(this.$refs.chartCanvas.getContext('2d'), data)
        },
        generateRandomColor(max) {
            return Math.floor(Math.random() * Math.floor(max));
        },
    },
    mounted () {
        this.renderChart()
    }
}
</script>
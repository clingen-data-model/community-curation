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
        clearChart() {
            if (this.chart !== null) {
                this.chart.destroy();
            }
        },
        renderChart(context, options) {
            this.clearChart();
            const data  = {
                type: 'pie',
                data: {
                    labels: Object.keys(this.chartData),
                    datasets: [{
                        label: 'test',
                        data: Object.values(this.chartData),
                        backgroundColor: (context) => {
                            if (this.colors.length < Object.keys(this.chartData).length) {
                                if (Object.keys(this.chartData)[context.dataIndex].toLowerCase() == 'unknown') {
                                    this.$set(this.colors, context.dataIndex, `rgba(100,100,100,.4)`)
                                    return;
                                }
                                this.$set(this.colors, context.dataIndex, this.generateRandomColor());
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
            const randNum = () => Math.floor(Math.random() * Math.floor(256));
            let color = `rgba(${randNum()},${randNum()},${randNum()},0.4)`;
            while (color == `rgba(100,100,100,0.4)`) {
                color = `rgba(${randNum()},${randNum()},${randNum()},0.4)`;
            }
            return color;
        },
    },
    mounted () {
        this.renderChart()
    }
}
</script>
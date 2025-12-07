<template>
    <div class="text-white">
        <canvas ref="canvasRef"></canvas>
    </div>
</template>

<script>
import { ref, watch, onMounted } from 'vue';
import Chart from 'chart.js/auto';

export default {
    name: 'BarChart',
    props: {
        values: {
            type: Array,
            required: true
        },
        labels: {
            type: Array,
            required: true
        }
    },
    setup(props) {
        const canvasRef = ref(null); // make sure this matches template
        let chart = null;

        const renderChart = () => {
            if (!canvasRef.value) return; // safety check
            if (chart) chart.destroy();   // destroy previous chart

            const ctx = canvasRef.value.getContext('2d'); // get context
            if (!ctx) {
                console.error('Failed to get 2D context from canvas!');
                return;
            }

            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: props.labels,
                    datasets: [
                        {
                            label: 'Card Costs',
                            data: props.values,
                            backgroundColor: '#41ad49'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        };

        watch(
            () => [props.values, props.labels],
            renderChart,
            { deep: true, immediate: true }
        );

        onMounted(() => {
            renderChart();
        });

        return { canvasRef };
    }
};
</script>

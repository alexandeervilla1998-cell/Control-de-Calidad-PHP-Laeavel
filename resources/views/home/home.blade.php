@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-10">
    <div>
        <h1 class="font-nav text-2xl font-bold text-gray-800 tracking-tight">Panel de Control</h1>
        <p class="text-sm text-gray-500 font-sans">Visualiza los reportes tanto de historiales como datos en vivo.</p>
    </div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="md:col-span-1">
        <h3 class="font-nav font-bold text-gray-800 tracking-tight">Promedio Humedad</h3>
        <canvas id="humedadPromedioChart" class="w-full" height="200"></canvas>
    </div>
    <div class="md:col-span-1">
        <h3 class="font-nav font-bold text-gray-800 tracking-tight">Promedio Temperatura</h3>
        <canvas id="temperaturaPromedioChart" class="w-full" height="200"></canvas>
    </div>
    <div class="md:col-span-1">
        <h3 class="font-nav font-bold text-gray-800 tracking-tight">Promedio Sodio</h3>
        <canvas id="sodioPromedioChart" class="w-full" height="200"></canvas>
    </div>
    <div class="md:col-span-1">
        <h3 class="font-nav font-bold text-gray-800 tracking-tight">Promedio Proteína</h3>
        <canvas id="proteinaPromedioChart" class="w-full" height="200"></canvas>
    </div>
</div>
    

<script>
    function obtenerDatos() {
        try {
            // extraemos los datos de la variable enviada por el controlador
            const lotes = @json($lotes);

            if (!lotes) {
                return;
            }
            

            // mapeamos los datos para los ejes del gráfico
            const labels = lotes.map(lote => `${lote.name}`);
            const avgHumedad = lotes.map(lote => parseFloat(lote.avg_moisture).toFixed(2));
            const avgTemp = lotes.map(lote => parseFloat(lote.avg_temperature).toFixed(2));
            const avgSodio = lotes.map(lote => parseFloat(lote.avg_sodium).toFixed(2));
            const avgProteina = lotes.map(lote => parseFloat(lote.avg_protein).toFixed(2));

            // obtenemos los contenedores canvas
            const humedadPromedioChart = document.getElementById('humedadPromedioChart');
            const temperaturaPromedioChart = document.getElementById('temperaturaPromedioChart');
            const sodioPromedioChart = document.getElementById('sodioPromedioChart');
            const proteinaPromedioChart = document.getElementById('proteinaPromedioChart');
            
            // tomamos los limites del primer registro
            const minHumedad = lotes[0].min_moisture*1;
            const maxHumedad = lotes[0].max_moisture*1;
            const minTemp = lotes[0].min_temperature*1;
            const maxTemp = lotes[0].max_temperature*1;
            const minSodio = lotes[0].min_sodium*1;
            const maxSodio = lotes[0].max_sodium*1;
            const minProt = lotes[0].min_protein*1;
            const maxProt = lotes[0].max_protein*1;

            // tomamos el min y max de los promedios
            const minAvgHumedad = Math.floor(Math.min(...avgHumedad));
            const maxAvgHumedad = Math.floor(Math.max(...avgHumedad));
            const minAvgTemp = Math.floor(Math.min(...avgTemp));
            const maxAvgTemp = Math.floor(Math.max(...avgTemp));
            const minAvgSodio = Math.floor(Math.min(...avgSodio));
            const maxAvgSodio = Math.floor(Math.max(...avgSodio));
            const minAvgProt = Math.floor(Math.min(...avgProteina));
            const maxAvgProt = Math.floor(Math.max(...avgProteina));
            
            // llenamos los graficos
            new Chart(humedadPromedioChart, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Humedad (%)',
                            data: avgHumedad,
                            borderColor: 'rgb(59, 130, 246)',
                            tension: 0.1
                        },
                        {
                            label: 'Min',
                            // creamos un array con el mismo valor para todos los lotes
                            data: lotes.map(() => minHumedad), 
                            type: 'line', // Esto convierte este dataset en una línea sobre las barras
                            borderColor: 'red',
                            borderDash: [5, 5], // Línea punteada
                            fill: false
                        },
                        {
                            label: 'Max',
                            data: lotes.map(() => maxHumedad), 
                            type: 'line',
                            borderColor: 'red',
                            borderDash: [5, 5],
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            min: minAvgHumedad < minHumedad ? minAvgHumedad - 0.5 : minHumedad - 0.5,
                            max: maxAvgHumedad > maxHumedad ? maxAvgHumedad + 0.5 : maxHumedad + 0.5,
                            grid: { color: '#f3f4f6' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { position: 'top', labels: { font: { family: 'sans-serif', size: 12 } } }
                    }
                }
            });
            
            new Chart(temperaturaPromedioChart, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Temperatura (°C)',
                            data: avgTemp,
                            borderColor: 'rgb(59, 130, 246)',
                            tension: 0.1
                        },
                        {
                            label: 'Min',
                            data: lotes.map(() => minTemp), 
                            type: 'line',
                            borderColor: 'red',
                            borderDash: [5, 5],
                            fill: false
                        },
                        {
                            label: 'Max',
                            data: lotes.map(() => maxTemp), 
                            type: 'line',
                            borderColor: 'red',
                            borderDash: [5, 5],
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            min: minAvgTemp < minTemp ? minAvgTemp - 1 : minTemp - 1,
                            max: maxAvgTemp > maxTemp ? maxAvgTemp + 1: maxTemp + 1,
                            grid: { color: '#f3f4f6' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { position: 'top', labels: { font: { family: 'sans-serif', size: 12 } } }
                    }
                }
            });
            
            new Chart(sodioPromedioChart, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Sodio (ppm)',
                            data: avgSodio,
                            borderColor: 'rgb(59, 130, 246)',
                            tension: 0.1
                        },
                        {
                            label: 'Min',
                            data: lotes.map(() => minSodio), 
                            type: 'line',
                            borderColor: 'red',
                            borderDash: [5, 5],
                            fill: false
                        },
                        {
                            label: 'Max',
                            data: lotes.map(() => maxSodio), 
                            type: 'line',
                            borderColor: 'red',
                            borderDash: [5, 5],
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            min: minAvgSodio < minSodio ? minAvgSodio - 1 : minSodio - 1,
                            max: maxAvgSodio > maxSodio ? maxAvgSodio + 1: maxSodio + 1,
                            grid: { color: '#f3f4f6' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { position: 'top', labels: { font: { family: 'sans-serif', size: 12 } } }
                    }
                }
            });
            
            new Chart(proteinaPromedioChart, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Proteína (%)',
                            data: avgProteina,
                            borderColor: 'rgb(59, 130, 246)',
                            tension: 0.1
                        },
                        {
                            label: 'Min',
                            data: lotes.map(() => minProt), 
                            type: 'line',
                            borderColor: 'red',
                            borderDash: [5, 5],
                            fill: false
                        },
                        {
                            label: 'Max',
                            data: lotes.map(() => maxProt), 
                            type: 'line',
                            borderColor: 'red',
                            borderDash: [5, 5],
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            min: minAvgProt < minProt ? minAvgProt - 1 : minProt - 1,
                            max: maxAvgProt > maxProt ? maxAvgProt + 1: maxProt + 1,
                            grid: { color: '#f3f4f6' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { position: 'top', labels: { font: { family: 'sans-serif', size: 12 } } }
                    }
                }
            });
        } catch (error) {
            console.log(error);
        }
    }
  
    document.addEventListener('DOMContentLoaded', function() {
        obtenerDatos();
    });
</script>
@endsection
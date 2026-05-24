@extends('layouts.app')

@section('title', 'Registro de Análisis')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="font-nav text-2xl font-bold text-gray-800 tracking-tight">Detalle y Registro de Análisis</h1>
            <p class="text-sm text-gray-500 font-sans">Visualiza y registra los valores de cada muestra.</p>
        </div>

        <button onclick="mostrarDialogo()" type="button" 
        class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-nav font-semibold rounded-lg shadow-sm transition-all transition-transform hover:scale-105">
            <i class="fas fa-plus mr-2"></i>
            Registrar Nuevo
        </button>
    </div>

    <div id="partialanalysis-container" class="bg-white shadow-sm overflow-hidden">
        <div class="text-center py-10"><span class="animate-pulse text-blue-600 font-bold">Cargando datos...</span></div>
    </div>
</div>

<!-- Dialogo modal -->
<div id="dlgRegistro" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative w-full md:w-1/2 top-20 mx-auto p-5 border shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Registro de datos</h3>
            <div class="space-y-6 pt-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-1">
                        <label for="moisture" class="block font-nav text-sm font-semibold text-gray-700 mb-2">Humedad (%)</label>
                        <input type="number" step="any" name="moisture" id="moisture" placeholder="Ej. 15" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-gray-400">
                    </div>
                    <div class="md:col-span-1">
                        <label for="temperature" class="block font-nav text-sm font-semibold text-gray-700 mb-2">Temperatura (C)</label>
                        <input type="number" step="any" name="temperature" id="temperature" placeholder="Ej. 30" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-gray-400">
                    </div>
                    <div class="md:col-span-1">
                        <label for="sodium" class="block font-nav text-sm font-semibold text-gray-700 mb-2">Sodio (ppm)</label>
                        <input type="number" step="any" name="sodium" id="sodium" placeholder="Ej. 10" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-gray-400">
                    </div>
                    <div class="md:col-span-1">
                        <label for="protein" class="block font-nav text-sm font-semibold text-gray-700 mb-2">Proteína (%)</label>
                        <input type="number" step="any" name="protein" id="protein" placeholder="Ej. 5" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-gray-400">
                    </div>
                    <div class="md:col-span-2">
                        <label for="number_batch" class="block font-nav text-sm font-semibold text-gray-700 mb-2">Número batch</label>
                        <input type="number" step="any" name="number_batch" id="number_batch" placeholder="Ej. 1" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-gray-400">
                    </div>
                </div>
                <div class="flex gap-2 px-4 py-3">
                    <button type="button" 
                        class="mt-2 px-4 py-2 bg-gray-300 rounded-md w-full" 
                        onclick="document.getElementById('dlgRegistro').classList.add('hidden')">
                        Cancelar
                    </button>
                    <button onclick="guardar()" type="button" 
                        class="mt-2 text-white px-4 py-2 bg-green-600 rounded-md w-full">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function mostrarDialogo() {
        // reiniciamos las inputs
        document.getElementById('moisture').value = null;
        document.getElementById('temperature').value = null;
        sodium = document.getElementById('sodium').value = null;
        protein = document.getElementById('protein').value = null;
        number_batch = document.getElementById('number_batch').value = null;
        document.getElementById('dlgRegistro').classList.remove('hidden');

    }

    async function cargarDatos() {
        try {
            // obtenemos el id directo del controlador
            const batch_analysis_id = {{ $batch_analysis_id }}
            const contenedor = document.getElementById('partialanalysis-container');

            const response = await fetch('/batch_analysis_line/show', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // necesario para rutas POST en web.php
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    batch_analysis_id: batch_analysis_id
                })
            })

            if (!response.ok) {
                accionarToast('Error en la respuesta', 'toast-errorJS', 'toastErrorMsg');
            }
            
            
            contenedor.innerHTML = await response.text();
        } catch (error) {
            accionarToast("Error grave al cargar los datos", 'toast-errorJS', 'toastErrorMsg');
        }
    }

    async function guardar() {
        try {
            
            const moisture = document.getElementById('moisture').value;
            const temperature = document.getElementById('temperature').value;
            const sodium = document.getElementById('sodium').value;
            const protein = document.getElementById('protein').value;
            const number_batch = document.getElementById('number_batch').value;
            // obtenemos el id directo del controlador
            const batch_analysis_id = {{ $batch_analysis_id }}

            const response = await fetch('/batch_analysis_line/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // necesario para rutas POST en web.php
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    moisture: moisture,
                    temperature: temperature,
                    sodium: sodium,
                    protein: protein,
                    number_batch: number_batch,
                    batch_analysis_id: batch_analysis_id
                })
            })

            if (!response.ok) {
                accionarToast('Error en la respuesta', 'toast-errorJS', 'toastErrorMsg');
            }
            
            var data = await response.json();
            var status = data.status;
            var message = data.message;
            
            if (status) {
                // cerramos el dialogo
                document.getElementById('dlgRegistro').classList.add('hidden')
                // mostramos el toast que todo se hizo bien
                accionarToast(message, 'toast-successJS', 'toastSuccesMsg');
                // recargamos los datos
                cargarDatos();
            } else {
                accionarToast(message, 'toast-errorJS', 'toastErrorMsg');   
            }
        } catch (error) {
            accionarToast("Error grave al procesar", 'toast-errorJS', 'toastErrorMsg');
        }
    }

    function accionarToast(message, id, idmsg) {
        // obtenemos el toast del app.blade.php
        var toastJS = document.getElementById(id);
        var toastMsg = document.getElementById(idmsg);

        toastMsg.textContent = message;
        toastJS.classList.remove('hidden');
        
        // lo cerramos despues de 5 segundos
        setTimeout(() => {
            toastMsg.textContent = null;
            toastJS.classList.add('hidden')
        }, 5000);
    }
    
    document.addEventListener("DOMContentLoaded", function() {
        cargarDatos();
    });
</script>
@endsection
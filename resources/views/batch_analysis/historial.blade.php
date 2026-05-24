@extends('layouts.app')

@section('title', 'Historial de Análisis')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="font-nav text-2xl font-bold text-gray-800 tracking-tight">Historial de Análisis</h1>
            <p class="text-sm text-gray-500 font-sans">Revice los análisis realizados en cada lote.</p>
        </div>
        <div>
            
        </div>
    </div>

    <div class="flex flex-col md:flex-row md:items-center gap-4">
        <div class="flex gap-2 md:items-center">
            <label class="w-full block font-nav text-sm font-semibold text-gray-700 mb-2">Fecha Análisis</label>
            <input type="date" name="date_front" id="date_front" class="uppercase w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition-all placeholder:text-gray-400">
        </div>
        <button onclick="buscarHistorialPorFecha()" type="button" class=" gap-2 inline-flex items-center justify-center px-10 py-2 bg-blue-600 hover:bg-blue-700 text-white font-nav font-semibold rounded-lg shadow-sm transition-all transition-transform hover:scale-105">
            <i class="fa-solid fa-search"></i>
            Buscar
        </button>
    </div>

    <div id="partialhistorial-container" class="bg-white shadow-sm overflow-hidden">
        <p class="text-gray-500 italic text-center py-10 font-sans">Seleccione los filtros para visualizar el historial.</p>
    </div>
</div>

<script>
    async function buscarHistorialPorFecha() {
        try {
            
            const date_front = document.getElementById('date_front').value;

            if (!date_front) {
                accionarToast('Error, debe seleccionar una fecha', 'toast-errorJS', 'toastErrorMsg');
                return;
            }

            var historialContainer = document.getElementById('partialhistorial-container');

            // Mostramos un estado de carga
            historialContainer.innerHTML = '<div class="text-center py-10"><span class="animate-pulse text-blue-600 font-bold">Cargando datos...</span></div>';

            const response = await fetch('/batch_analysis/historial', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // necesario para rutas POST en web.php
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    date_front: date_front
                })
            });

            if (!response.ok) {
                accionarToast('Error en la respuesta', 'toast-errorJS', 'toastErrorMsg');
            }
            
            historialContainer.innerHTML = await response.text();
        } catch (error) {
            accionarToast("Error grave al cargar los datos", 'toast-errorJS', 'toastErrorMsg');
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
</script>
@endsection
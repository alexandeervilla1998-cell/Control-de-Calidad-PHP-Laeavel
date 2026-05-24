@extends('layouts.app')

@section('title', 'Detalle de Análisis')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="font-nav text-2xl font-bold text-gray-800 tracking-tight">Detalle de Análisis</h1>
            <p class="text-sm text-gray-500 font-sans">Visualiza los valores de cada muestra.</p>
        </div>
    </div>

    <div id="partialanalysis-container" class="bg-white shadow-sm overflow-hidden">
        <div class="text-center py-10"><span class="animate-pulse text-blue-600 font-bold">Cargando datos...</span></div>
    </div>
</div>

<script>
    async function buscarDetalleAnalisisPorBatchId() {
        try {

            const batch_analysis_id = {{ $batch_analysis_id }};
            var analysisContainer = document.getElementById('partialanalysis-container');

            const response = await fetch('/batch_analysis_line/show', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    batch_analysis_id: batch_analysis_id
                })
            })

            if (!response.ok) {
                accionarToast('Error en la respuesta', 'toast-errorJS', 'toastErrorMsg');
            }

            analysisContainer.innerHTML = await response.text();
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

    document.addEventListener('DOMContentLoaded', () => {
        buscarDetalleAnalisisPorBatchId();
    });
</script>
@endsection
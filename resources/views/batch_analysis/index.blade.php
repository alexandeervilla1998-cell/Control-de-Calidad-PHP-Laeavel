@extends('layouts.app')

@section('title', 'Lotes en Proceso de Análisis')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="font-nav text-2xl font-bold text-gray-800 tracking-tight">Lotes en Proceso de Análisis</h1>
            <p class="text-sm text-gray-500 font-sans">Se lleva el control del tiempo y total de análisis realizados.</p>
        </div>
    </div>

    <div class="bg-white shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-3 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">ID Lote</th>
                    <th class="px-3 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Lote</th>
                    <th class="px-3 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Producto</th>
                    <th class="px-3 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Línea de Producción</th>
                    <th class="px-3 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha Inicio Análisis</th>
                    <th class="px-3 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Tiempo Ocupado</th>
                    <th class="px-3 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Análiysis Realizados</th>
                    <th class="px-3 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 font-sans">
                @forelse ($batchsAnalysis as $batchAnalysis)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-3 py-4 text-sm font-semibold text-gray-900">{{ $batchAnalysis->lot_id }}</td>
                        <td class="px-3 py-4 text-sm font-semibold text-gray-900">{{ $batchAnalysis->lot->name }}</td>
                        <td class="px-3 py-4 text-sm text-gray-500">{{ $batchAnalysis->lot->product->name }}</td>
                        <td class="px-3 py-4 text-sm text-gray-500">{{ $batchAnalysis->lot->product->productionLine->name }}</td>
                        <td class="px-3 py-4 text-sm text-gray-500">{{ $batchAnalysis->batchAnalysisState->datefrom }}</td>
                        <td class="px-3 py-4 text-sm font-semibold text-red-500 timer" 
                            data-start-date="{{ $batchAnalysis->batchAnalysisState->datefrom }}"
                            id="timer-{{ $batchAnalysis->batch_analysis_id }}">
                            00:00:00
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-500"> {{ $batchAnalysis->batchAnalysisLines->count() }} </td>
                        <td class="px-3 py-4 text-right">
                            <div class="flex gap-2">
                                <form action="{{ route('batch_analysis.update', $batchAnalysis->batch_analysis_id) }}" method="POST" 
                                    onsubmit="return confirm('¿Estás seguro de finalizar el proceso de {{ $batchAnalysis->lot->name }}?');"
                                    class="inline">
                                    @csrf
                                    
                                    <button title="Finalizar proceso" type="submit" class="text-xs p-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none">
                                        Finalizar
                                    </button>
                                </form>
                                <form action="{{ route('batch_analysis_line.index') }}" 
                                    method="POST" class="inline" target="_blank">
                                    @csrf
                                    <input class="hidden" type="number" name="batch_analysis_id" value="{{$batchAnalysis->batch_analysis_id}}">
                                    <button type="submit" class="text-xs p-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none">
                                        Agregar Análisis
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
                            No se encontraron registrados en el sistema.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    
    const actualizarCronometros = () => {
        const ahora = new Date().getTime();
        const timers = document.querySelectorAll('.timer');

        timers.forEach(timer => {
            const fechaInicioStr = timer.getAttribute('data-start-date');
            const fechaInicio = new Date(fechaInicioStr).getTime();
            
            // Calculamos la diferencia en milisegundos
            const diferencia = ahora - fechaInicio;
            
            if (diferencia > 0) {
                const horas = Math.floor((diferencia / (1000 * 60 * 60)));
                const minutos = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));
                //const segundos = Math.floor((diferencia % (1000 * 60)) / 1000);

                // Formateo con ceros a la izquierda
                //const hDisplay = String(horas).padStart(2, '0');
                const mDisplay = String(minutos).padStart(2, '0');
                //const sDisplay = String(segundos).padStart(2, '0');
                
                if (horas > 0) {
                    timer.textContent = `${horas} hrs y ${mDisplay} min`;
                } else {
                    timer.textContent = `${minutos} min`;
                }
            }
        });
    };

    // Ejecutar cada minuto
    setInterval(actualizarCronometros, 10000);
    
    // Ejecutar una vez al cargar
    actualizarCronometros();
});
</script>
@endsection
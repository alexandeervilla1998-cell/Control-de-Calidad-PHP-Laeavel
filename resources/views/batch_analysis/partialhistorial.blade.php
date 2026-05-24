<table class="w-full text-left border-collapse">
    <thead class="bg-gray-50 border-b border-gray-200">
        <tr>
            <th class="px-3 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">ID Lote</th>
            <th class="px-3 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Lote</th>
            <th class="px-3 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Producto</th>
            <th class="px-3 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Línea de Producción</th>
            <th class="px-3 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha Inicio Análisis</th>
            <th class="px-3 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha Fin Análisis</th>
            <th class="px-3 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Tiempo Ocupado</th>
            <th class="px-3 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Análiysis Realizados</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-100 font-sans">
        @forelse ($batchsAnalysis as $batchAnalysis)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-3 py-4 text-sm font-semibold text-gray-900">{{ $batchAnalysis->lot_id }}</td>
                <td class="px-3 py-4 text-sm font-semibold text-gray-900">{{ $batchAnalysis->lot->name }}</td>
                <td class="px-3 py-4 text-sm text-gray-500">{{ $batchAnalysis->lot->product->name }}</td>
                <td class="px-3 py-4 text-sm text-gray-500">{{ $batchAnalysis->lot->product->productionLine->name }}</td>
                <td class="px-3 py-4 text-sm text-gray-500">{{ $batchAnalysis->batchAnalysisState->datefrom->format('d M Y h:i A') }}</td>
                <td class="px-3 py-4 text-sm text-gray-500">{{ $batchAnalysis->batchAnalysisState->dateto->format('d M Y h:i A') }}</td>
                <td class="px-3 py-4 text-sm font-semibold text-red-500"> {{ $batchAnalysis->batchAnalysisState->date_diff_str }}</td>
                <td class="px-3 py-4 text-sm text-gray-500">
                    <form action="{{ route('batch_analysis_line.detail') }}" 
                        method="POST" class="inline" target="_blank">
                        @csrf
                        <input class="hidden" type="number" name="batch_analysis_id" value="{{$batchAnalysis->batch_analysis_id}}">
                        <button type="submit" 
                            class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-md transition-colors flex gap-3 items-center" 
                            title="Ver detalle análisis">
                            {{ $batchAnalysis->batchAnalysisLines->count() }}
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </button>
                    </form>
                    
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
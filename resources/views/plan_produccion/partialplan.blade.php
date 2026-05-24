@if ($lots->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Lote</th>
                    <th class="px-6 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Producto</th>
                    <th class="px-6 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Línea de Producción</th>
                    <th class="px-6 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha Producción</th>
                    <th class="px-6 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider text-right">Opciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 font-sans">
                @forelse($lots as $lot)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-500 text-center">{{ $lot->lot_id }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900">{{ $lot->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900">{{ $lot->product->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900">{{ $lot->product->productionLine->name }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $lot->production_date->format('d M Y h:i A') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @if ($lot->batchAnalysis != null)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $lot->batchAnalysis->batchAnalysisState->name }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    No analizado
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-center">
                                @if ($lot->batchAnalysis != null)
                                    <button type="button" disabled class="p-1.5 text-gray-600 hover:bg-grey-50 rounded-md transition-colors" title="Procesar">Procesar</button>
                                @else
                                    <button onclick="procesar({{ $lot->lot_id }})" type="button" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-md transition-colors" title="Procesar">Procesar</button>
                                @endif
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
@else 
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
        <p class="text-yellow-700 text-sm">No hay planes programados para esa fecha y línea.</p>
    </div>
@endif
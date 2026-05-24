<table class="w-full text-left border-collapse">
    <thead class="bg-gray-50 border-b border-gray-200">
        <tr>
            <th class="px-6 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">ID Análisis</th>
            <th class="px-6 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Lote</th>
            <th class="px-6 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Producto</th>
            <th class="px-6 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha Análisis</th>
            <th class="px-6 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Humedad (%)</th>
            <th class="px-6 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Temperatura (°C)</th>
            <th class="px-6 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Sodio (ppm)</th>
            <th class="px-6 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Proteína (%)</th>
            <th class="px-6 py-4 font-nav text-xs font-semibold text-gray-600 uppercase tracking-wider">Número Batch</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-100 font-sans">
        @forelse ($lines as $line)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $line->batch_analysis_line_id }}</td>
                <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $line->batchsAnalysis->lot->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $line->batchsAnalysis->lot->product->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $line->created->format('d M Y h:i A') }}</td>
                @if ($line->moisture_state == 'aceptable')
                    <td class="px-6 py-4 text-sm text-green-500" 
                    title="Estado: {{$line->moisture_state }}, Min: {{ $line->parameters->min_moisture }}, Max: {{ $line->parameters->max_moisture }}">
                        {{ $line->moisture }} <i class="fa-solid fa-check"></i>
                    </td>
                @else
                    <td class="px-6 py-4 text-sm text-red-500" 
                    title="Estado: {{$line->moisture_state }}, Min: {{ $line->parameters->min_moisture }}, Max: {{ $line->parameters->max_moisture }}">
                        {{ $line->moisture }}
                        @if ($line->moisture_state == 'bajo')
                            <i class="fa-solid fa-arrow-down"></i>
                        @else
                            <i class="fa-solid fa-arrow-up"></i>
                        @endif
                    </td>
                @endif
                @if ($line->temperature_state == 'aceptable')
                    <td class="px-6 py-4 text-sm text-green-500" 
                    title="Estado: {{$line->temperature_state }}, Min: {{ $line->parameters->min_temperature }}, Max: {{ $line->parameters->max_temperature }}">
                        {{ $line->temperature }} <i class="fa-solid fa-check"></i>
                    </td>
                @else
                    <td class="px-6 py-4 text-sm text-red-500" 
                    title="Estado: {{$line->temperature_state }}, Min: {{ $line->parameters->min_temperature }}, Max: {{ $line->parameters->max_temperature }}">
                        {{ $line->temperature }}
                        @if ($line->temperature_state == 'bajo')
                            <i class="fa-solid fa-arrow-down"></i>
                        @else
                            <i class="fa-solid fa-arrow-up"></i>
                        @endif
                    </td>
                @endif
                @if ($line->sodium_state == 'aceptable')
                    <td class="px-6 py-4 text-sm text-green-500" 
                    title="Estado: {{$line->sodium_state }}, Min: {{ $line->parameters->min_sodium }}, Max: {{ $line->parameters->max_sodium }}">
                        {{ $line->sodium }} <i class="fa-solid fa-check"></i>
                    </td>
                @else
                    <td class="px-6 py-4 text-sm text-red-500" 
                    title="Estado: {{$line->sodium_state }}, Min: {{ $line->parameters->min_sodium }}, Max: {{ $line->parameters->max_sodium }}">
                        {{ $line->sodium }}
                        @if ($line->sodium_state == 'bajo')
                            <i class="fa-solid fa-arrow-down"></i>
                        @else
                            <i class="fa-solid fa-arrow-up"></i>
                        @endif
                    </td>
                @endif
                @if ($line->protein_state == 'aceptable')
                    <td class="px-6 py-4 text-sm text-green-500" 
                    title="Estado: {{$line->protein_state }}, Min: {{ $line->parameters->min_protein }}, Max: {{ $line->parameters->max_protein }}">
                        {{ $line->protein }} <i class="fa-solid fa-check"></i>
                    </td>
                @else
                    <td class="px-6 py-4 text-sm text-red-500" 
                    title="Estado: {{$line->protein_state }}, Min: {{ $line->parameters->min_protein }}, Max: {{ $line->parameters->max_protein }}">
                        {{ $line->protein }}
                        @if ($line->protein_state == 'bajo')
                            <i class="fa-solid fa-arrow-down"></i>
                        @else
                            <i class="fa-solid fa-arrow-up"></i>
                        @endif
                    </td>
                @endif
                <td class="px-6 py-4 text-sm text-gray-500">{{ $line->number_batch }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="px-6 py-10 text-center text-gray-500 italic">
                    No se encontraron registrados en el sistema.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
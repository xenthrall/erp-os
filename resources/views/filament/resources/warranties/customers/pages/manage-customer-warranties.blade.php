<x-filament-panels::page>
    @php
        // Obtenemos las garantías ordenadas de la más reciente a la más antigua
        $warranties = $this->record->warrantyRequests()->latest()->get();
    @endphp

    @if($warranties->isEmpty())
        {{-- Mensaje si no hay garantías --}}
        <div class="p-6 text-center bg-white rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <x-heroicon-o-shield-exclamation class="w-12 h-12 mx-auto text-gray-400" />
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Sin garantías</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Este cliente aún no tiene solicitudes de garantía registradas.</p>
        </div>
    @else
        {{-- Grid de Tarjetas de Garantía --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach($warranties as $warranty)
                <div class="flex flex-col p-4 bg-white rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                    
                    {{-- Encabezado de la tarjeta: Modelo y Estado --}}
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white text-md">
                                {{ $warranty->model ?? 'Modelo no especificado' }}
                            </h3>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                Registrada el {{ $warranty->created_at->format('d M, Y') }}
                            </span>
                        </div>
                        
                        {{-- Badge de estado básico --}}
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-medium
                            @if($warranty->status === 'approved') bg-success-100 text-success-700 dark:bg-success-900/30 dark:text-success-400
                            @elseif($warranty->status->value === 'rejected') bg-danger-100 text-danger-700 dark:bg-danger-900/30 dark:text-danger-400
                            @elseif($warranty->status->value === 'pending') bg-warning-100 text-warning-700 dark:bg-warning-900/30 dark:text-warning-400
                            @else bg-info-100 text-info-700 dark:bg-info-900/30 dark:text-info-400
                            @endif
                        ">
                            {{ ucfirst($warranty->status->value) }}
                        </span>
                    </div>

                    {{-- Detalles del cuerpo --}}
                    <div class="flex-1 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex justify-between">
                            <span class="font-medium">Cantidad:</span>
                            <span>{{ $warranty->quantity }} unds.</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Fábrica:</span>
                            <span>{{ $warranty->factory ? $warranty->factory->name : 'No asignada' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Cód. Interno:</span>
                            <span>{{ $warranty->internal_code ?? '-' }}</span>
                        </div>
                    </div>

                    {{-- Descripción de falla --}}
                    @if($warranty->failure_description)
                        <div class="pt-3 mt-4 border-t border-gray-100 dark:border-white/10">
                            <span class="block mb-1 text-xs font-medium text-gray-900 dark:text-gray-300">Falla reportada:</span>
                            <p class="text-sm text-gray-500 line-clamp-2 dark:text-gray-400" title="{{ $warranty->failure_description }}">
                                {{ $warranty->failure_description }}
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</x-filament-panels::page>
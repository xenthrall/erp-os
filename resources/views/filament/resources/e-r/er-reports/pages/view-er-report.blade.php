<x-filament-panels::page>
    <div class="space-y-6">

        {{-- 1. Tarjeta de Información General --}}
        <x-filament::section icon="heroicon-o-information-circle" heading="Detalles del Reporte">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Empleado Involucrado --}}
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Empleado</span>
                    <p class="mt-1 text-base font-semibold">{{ $record->employee->full_name ?? 'N/A' }}</p>
                </div>

                {{-- Reportado por --}}
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Reportado por</span>
                    <p class="mt-1 text-base">{{ $record->reporter->name ?? 'N/A' }}</p>
                </div>

                {{-- Fecha del Evento --}}
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha del Evento</span>
                    <p class="mt-1 text-base">
                        {{ $record->event_date ? \Carbon\Carbon::parse($record->event_date)->translatedFormat('d F Y') : 'N/A' }}
                    </p>
                </div>

                {{-- Monto de Descuento --}}
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Monto de Descuento</span>
                    <p class="mt-1 text-base">
                        {{ $record->discount_amount ? '$' . number_format($record->discount_amount, 2) : 'No aplica' }}
                    </p>
                </div>

                {{-- Estado (¡Actualizado con tus colores y textos!) --}}
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Estado Actual</span>
                    <div class="mt-1">
                        <x-filament::badge :color="match ($record->status) {
                            'abierto' => 'danger',
                            'en_proceso' => 'warning',
                            'cerrado' => 'success',
                            default => 'gray',
                        }">
                            {{ match ($record->status) {
                                'abierto' => 'Abierto',
                                'en_proceso' => 'En proceso',
                                'cerrado' => 'Cerrado',
                                default => ucfirst($record->status ?? 'Desconocido'),
                            } }}
                        </x-filament::badge>
                    </div>
                </div>

            </div>
        </x-filament::section>

        {{-- 1.5. Tarjeta de Detalles del Tipo de Evento (ErType) --}}
        <x-filament::section icon="heroicon-o-tag"
            heading="Clasificación del Evento: {{ $record->type->name ?? 'N/A' }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Descripción del tipo de falta --}}
                <div class="md:col-span-2 lg:col-span-3">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Descripción de la Política /
                        Falta</span>
                    <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">
                        {{ $record->type->description ?? 'No hay una descripción establecida para este tipo de evento.' }}
                    </p>
                </div>

                {{-- Nivel de Severidad (¡Actualizado con emojis y colores!) --}}
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Gravedad</span>
                    <div class="mt-1">
                        <x-filament::badge :color="match ($record->type->severity) {
                            'leve' => 'success',
                            'moderado' => 'warning',
                            'grave' => 'danger',
                            'critico' => 'gray',
                            default => 'gray',
                        }">
                            {{ match ($record->type->severity) {
                                'leve' => '🟢 Leve',
                                'moderado' => '🟡 Moderado',
                                'grave' => '🔴 Grave',
                                'critico' => '💥 Crítico',
                                default => ucfirst($record->type->severity ?? 'No definida'),
                            } }}
                        </x-filament::badge>
                    </div>
                </div>

                {{-- Penalidad en Comisiones --}}
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">¿Afecta Comisiones?</span>
                    <div class="mt-1">
                        @if ($record->type->has_commission_penalty)
                            <x-filament::badge color="danger" icon="heroicon-o-exclamation-triangle">
                                SÍ, APLICA PENALIDAD
                            </x-filament::badge>
                        @else
                            <x-filament::badge color="success" icon="heroicon-o-check-circle">
                                NO AFECTA
                            </x-filament::badge>
                        @endif
                    </div>
                </div>

                {{-- Porcentaje de Penalidad (Solo se muestra si aplica) --}}
                @if ($record->type->has_commission_penalty)
                    <div>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Porcentaje a Descontar</span>
                        <p class="mt-1 text-lg font-bold text-danger-600 dark:text-danger-400">
                            {{ $record->type->commission_penalty_percentage }}%
                        </p>
                    </div>
                @endif

            </div>
        </x-filament::section>

        {{-- Nueva Tarjeta: Referencias Asociadas --}}
        @php
            $hasReferences =
                !empty($record->references) &&
                (!empty($record->references['invoice']['number']) ||
                    !empty($record->references['invoice']['url']) ||
                    !empty($record->references['ticket']['number']) ||
                    !empty($record->references['ticket']['url']) ||
                    !empty($record->references['order']['number']) ||
                    !empty($record->references['order']['url']));
        @endphp

        @if ($hasReferences)
            <x-filament::section icon="heroicon-o-link" heading="Referencias Asociadas">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    {{-- Factura --}}
                    @if (!empty($record->references['invoice']['number']) || !empty($record->references['invoice']['url']))
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Factura</span>
                            <div class="mt-1 flex items-center gap-2">
                                @if (!empty($record->references['invoice']['number']))
                                    <span
                                        class="text-base font-semibold text-gray-900 dark:text-white">{{ $record->references['invoice']['number'] }}</span>
                                @else
                                    <span class="text-base italic text-gray-400">Sin número</span>
                                @endif

                                @if (!empty($record->references['invoice']['url']))
                                    <a href="{{ $record->references['invoice']['url'] }}" target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-500 hover:underline transition">
                                        <x-filament::icon icon="heroicon-o-arrow-top-right-on-square" class="w-4 h-4" />
                                        <span>Ver enlace</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Ticket --}}
                    @if (!empty($record->references['ticket']['number']) || !empty($record->references['ticket']['url']))
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Ticket</span>
                            <div class="mt-1 flex items-center gap-2">
                                @if (!empty($record->references['ticket']['number']))
                                    <span
                                        class="text-base font-semibold text-gray-900 dark:text-white">{{ $record->references['ticket']['number'] }}</span>
                                @else
                                    <span class="text-base italic text-gray-400">Sin número</span>
                                @endif

                                @if (!empty($record->references['ticket']['url']))
                                    <a href="{{ $record->references['ticket']['url'] }}" target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-500 hover:underline transition">
                                        <x-filament::icon icon="heroicon-o-arrow-top-right-on-square" class="w-4 h-4" />
                                        <span>Ver enlace</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Orden --}}
                    @if (!empty($record->references['order']['number']) || !empty($record->references['order']['url']))
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Orden</span>
                            <div class="mt-1 flex items-center gap-2">
                                @if (!empty($record->references['order']['number']))
                                    <span
                                        class="text-base font-semibold text-gray-900 dark:text-white">{{ $record->references['order']['number'] }}</span>
                                @else
                                    <span class="text-base italic text-gray-400">Sin número</span>
                                @endif

                                @if (!empty($record->references['order']['url']))
                                    <a href="{{ $record->references['order']['url'] }}" target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-500 hover:underline transition">
                                        <x-filament::icon icon="heroicon-o-arrow-top-right-on-square" class="w-4 h-4" />
                                        <span>Ver enlace</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>
            </x-filament::section>
        @endif

        {{-- 2. Tarjeta de Descripción --}}
        <x-filament::section icon="heroicon-o-document-text" heading="Descripción de los hechos">
            <div class="prose dark:prose-invert max-w-none text-sm text-gray-700 dark:text-gray-300">
                {!! nl2br(e($record->description)) !!}
            </div>
        </x-filament::section>

        {{-- Tarjeta de Solución --}}
        <x-filament::section icon="heroicon-o-check-circle" heading="Solución Implementada">
            <div class="prose dark:prose-invert max-w-none text-sm text-gray-700 dark:text-gray-300">
                @if ($record->solution)
                    {!! nl2br(e($record->solution)) !!}
                @else
                    <p class="italic text-gray-500 dark:text-gray-400">
                        Aún no se ha registrado una solución para este reporte.
                    </p>
                @endif
            </div>
        </x-filament::section>

        {{-- 3. Tarjeta de Evidencias (Grid) --}}
        <x-filament::section icon="heroicon-o-paper-clip" heading="Evidencias Adjuntas">
            @if ($record->attachments->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach ($record->attachments as $attachment)
                        <a href="{{ Storage::disk('public')->url($attachment->path) }}" target="_blank"
                            class="flex flex-col items-center justify-center p-4 border rounded-xl hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800 transition shadow-sm">
                            <x-filament::icon icon="heroicon-o-document" class="w-10 h-10 text-primary-500 mb-2" />
                            <span class="text-xs text-center truncate w-full text-gray-600 dark:text-gray-300"
                                title="{{ basename($attachment->path) }}">
                                {{ basename($attachment->path) }}
                            </span>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 italic">No hay evidencias adjuntas para este reporte.</p>
            @endif
        </x-filament::section>

        {{-- 4. Bitácora / Historial de Observaciones (ErNote) --}}
        <x-filament::section icon="heroicon-o-clipboard-document-list" heading="Bitácora de Observaciones">
            @if ($record->notes->count() > 0)
                <div class="space-y-4">
                    {{-- Ordenamos la colección para mostrar las más recientes arriba --}}
                    @foreach ($record->notes->sortByDesc('created_at') as $note)
                        <div class="flex gap-x-3">
                            {{-- Línea vertical conectora y círculo del icono --}}
                            <div
                                class="relative last:after:hidden after:absolute after:top-7 after:bottom-0 after:start-3.5 after:w-px after:-translate-x-[0.5px] after:bg-gray-200 dark:after:bg-gray-700">
                                <div
                                    class="relative z-10 flex size-7 items-center justify-center rounded-full bg-primary-50 dark:bg-primary-900/50 ring-2 ring-white dark:ring-gray-900 shadow-sm">
                                    <x-filament::icon icon="heroicon-o-user"
                                        class="size-4 text-primary-600 dark:text-primary-400" />
                                </div>
                            </div>

                            {{-- Contenido de la nota --}}
                            <div class="grow pt-0.5 pb-6">
                                <div class="flex justify-between items-center mb-1">
                                    <h3 class="font-semibold text-gray-900 dark:text-white text-sm">
                                        {{ $note->user->name ?? 'Usuario del sistema' }}
                                    </h3>
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400"
                                        title="{{ $note->created_at->format('d/m/Y h:i A') }}">
                                        {{ $note->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                {{-- 👇 Aquí agregamos "break-words" para evitar que el texto se desborde --}}
                                <div
                                    class="mt-2 text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800/50 p-3.5 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm break-words">
                                    {!! nl2br(e($note->note)) !!}
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center p-6 text-gray-500 dark:text-gray-400">
                    <x-filament::icon icon="heroicon-o-chat-bubble-left-right"
                        class="w-10 h-10 mb-3 text-gray-300 dark:text-gray-600" />
                    <p class="text-sm italic">No hay registros en la bitácora aún.</p>
                </div>
            @endif
        </x-filament::section>

    </div>
</x-filament-panels::page>

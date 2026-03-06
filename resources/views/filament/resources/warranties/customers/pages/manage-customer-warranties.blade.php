<x-filament-panels::page>

    {{-- Tabs --}}
    <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
        <nav class="flex gap-6 text-sm font-medium">

            <button
                wire:click="setTab('requests')"
                class="pb-2 border-b-2
                    {{ $activeTab === 'requests'
                        ? 'border-primary-600 text-primary-600'
                        : 'border-transparent text-gray-500 hover:text-gray-700' }}"
            >
                Solicitudes
            </button>

            <button
                wire:click="setTab('batches')"
                class="pb-2 border-b-2
                    {{ $activeTab === 'batches'
                        ? 'border-primary-600 text-primary-600'
                        : 'border-transparent text-gray-500 hover:text-gray-700' }}"
            >
                Lotes
            </button>

            <button
                wire:click="setTab('all-requests')"
                class="pb-2 border-b-2
                    {{ $activeTab === 'all-requests'
                        ? 'border-primary-600 text-primary-600'
                        : 'border-transparent text-gray-500 hover:text-gray-700' }}"
            >
                Todas las solicitudes
            </button>

        </nav>
    </div>

    {{-- TAB SOLICITUDES --}}
    @if ($activeTab === 'requests')
        {{ $this->table }}
    @endif

    {{-- TAB LOTES --}}
    @if ($activeTab === 'batches')
        <livewire:warranties.asesor.customer-warranty-batches-table
            :customer="$this->record"
            :key="'batches-'.$this->record->id"
        />
    @endif

    {{-- TAB TODAS LAS SOLICITUDES --}}
    @if ($activeTab === 'all-requests')
        <livewire:warranties.asesor.customer-warranty-requests-table
            :customer="$this->record"
            :key="'all-requests-'.$this->record->id"
        />
    @endif

</x-filament-panels::page>
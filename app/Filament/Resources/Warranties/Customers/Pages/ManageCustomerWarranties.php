<?php

namespace App\Filament\Resources\Warranties\Customers\Pages;

use App\Filament\Resources\Warranties\Customers\CustomerResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class ManageCustomerWarranties extends Page
{
    use InteractsWithRecord;

    #protected static ?string $title = '';

    protected static string $resource = CustomerResource::class;

    protected string $view = 'filament.resources.warranties.customers.pages.manage-customer-warranties';

    public function getTitle(): string
    {
        return 'Garantías de ' . $this->record->first_name . ' ' . $this->record->last_name;
    }

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }
}

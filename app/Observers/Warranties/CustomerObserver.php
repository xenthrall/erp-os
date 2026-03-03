<?php

namespace App\Observers\Warranties;

use App\Models\Warranties\Customer;
use Illuminate\Validation\ValidationException;

class CustomerObserver
{
    public function updated(Customer $customer): void
    {
        // Sincronizar nombre del user si cambia
        if ($customer->wasChanged(['first_name', 'last_name'])) {

            $user = $customer->user;

            if ($user) {
                $newName = $customer->first_name . ' ' . $customer->last_name;

                if ($user->name !== $newName) {
                    $user->update([
                        'name' => $newName,
                    ]);
                }
            }
        }
    }

    public function deleting(Customer $customer): void
    {
        // No permitir eliminar si tiene solicitudes de garantía
        if ($customer->warrantyRequests()->exists()) {
            throw ValidationException::withMessages([
                'customer' => 'No se puede eliminar el cliente porque tiene solicitudes de garantía asociadas.'
            ]);
        }

        // Si tiene usuario asociado, eliminarlo también
        if ($customer->user) {

            $user = $customer->user;

            $user->delete();
        }
    }
}
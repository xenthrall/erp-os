<?php

namespace App\Observers\HR;

use App\Models\HR\Employee;
use Illuminate\Validation\ValidationException;

class EmployeeObserver
{
    public function updated(Employee $employee): void
    {
        // Solo si cambió el nombre o apellido
        if ($employee->wasChanged(['first_name', 'last_name'])) {

            $user = $employee->user;
            if ($user) {
                $newName = $employee->first_name . ' ' . $employee->last_name;

                // Evita update innecesario
                if ($user->name !== $newName) {
                    $user->update([
                        'name' => $newName,
                    ]);
                }
            }
        }
    }

    public function deleting(Employee $employee): void
    {
        if ($employee->errorReports()->exists()) {
            throw ValidationException::withMessages([
                'employee' => 'No se puede eliminar el empleado porque tiene reportes asociados.'
            ]);
        }

        if ($employee->user) {

            $user = $employee->user;

            if (
                $user->warrantyRequests()->exists() ||
                $user->reportedErReports()->exists()
            ) {
                throw ValidationException::withMessages([
                    'user' => 'No se puede eliminar el empleado porque su usuario tiene operaciones registradas.'
                ]);
            }

            $user->delete();
        }
    }
}
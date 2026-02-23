<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected array $permissionsToSync = [];

    protected function getHeaderActions(): array
    {
        $actions = [];

        if (Auth::user()?->can('admin.manage_roles')) {
            $actions[] = DeleteAction::make()
                ->label('Eliminar Rol');
        }

        return $actions;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $nested = $data['role_permissions'] ?? [];

        // 1. Obtenemos los permisos que el formulario envió (forzados a String para evitar errores)
        $submittedPermissions = collect($nested)
            ->flatMap(fn($arr) => is_array($arr) ? $arr : [])
            ->filter()
            ->values()
            ->map(fn($id) => (string) $id)
            ->toArray();

        // 2. lista estricta de permisos sensibles
        $sensitivePermissionNames = [
            'admin.manage_roles',
        ];
        //si el rol es SUper Admin definimos una lista de permisos sensibles más amplia
        if ($this->record->name === 'SUPER ADMIN') {
            $sensitivePermissionNames = [
                'admin.manage_roles',
                'admin.manage_users',
            ];
        }

        // 3. Obtenemos los IDs reales de esos permisos en la BD
        $sensitiveIds = Permission::whereIn('name', $sensitivePermissionNames)
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();

        // 4. APLICAMOS LA PROTECCIÓN DEL BACKEND
        if ($this->record->name === 'SUPER ADMIN') {
            // Si es Super Admin, unimos lo enviado + los sensibles (así garantizamos que nunca los pierda)
            $this->permissionsToSync = array_unique(array_merge($submittedPermissions, $sensitiveIds));
        } else {
            // Si NO es Super Admin, le restamos los IDs sensibles a lo que haya enviado
            $this->permissionsToSync = array_diff($submittedPermissions, $sensitiveIds);
        }

        // 5. Limpiamos el array temporal de Filament para no causar error en SQLite
        unset($data['role_permissions']);
        return $data;
    }

    protected function afterSave(): void
    {
        if (! empty($this->permissionsToSync)) {
            $permissions = Permission::whereIn('id', $this->permissionsToSync)->get();
            $this->record->syncPermissions($permissions);
        } else {
            $this->record->syncPermissions([]); 
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $ids = $this->record->permissions()->pluck('id')->toArray();

        $grouped = Permission::whereIn('id', $ids)
            ->get()
            ->groupBy('group')
            ->map(fn($items) => $items->pluck('id')->map(fn($id) => (string) $id)->toArray()) // Forzamos a string aquí también
            ->toArray();

        $data['role_permissions'] = $grouped;

        return $data;
    }
}
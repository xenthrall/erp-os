<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SetupRolesAndPermissions extends Command
{
    protected $signature = 'app:setup-roles-and-permissions';

    protected $description = 'Configura y sincroniza los roles y permisos base del sistema según la matriz de ER.';

    public function handle()
    {
        $this->info('🔐 Iniciando configuración de roles y permisos...');

        // 1. Limpiar caché del paquete Spatie
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // 2. Limpiar SOLAMENTE permisos y sus asignaciones (Protegiendo los Roles)
        $this->warn('⚠ Limpiando permisos antiguos (Los roles y usuarios se mantienen intactos)...');

        DB::transaction(function () {
            DB::table('role_has_permissions')->delete();
            DB::table('model_has_permissions')->delete();
            Permission::query()->delete();
        });

        $this->info('✔ Permisos eliminados correctamente.');

        // 3. Definir la matriz de permisos y a qué roles pertenecen
        $permissionsData = [
            'ROLES DEL SISTEMA' => [
                ['name' => 'roles.view', 'action' => 'Ver listado de roles', 'roles' => ['SUPER ADMIN']],
                ['name' => 'roles.create', 'action' => 'Crear roles', 'roles' => ['SUPER ADMIN']],
                ['name' => 'roles.edit', 'action' => 'Editar nombre del rol', 'roles' => ['SUPER ADMIN']],
                ['name' => 'roles.delete', 'action' => 'Eliminar roles', 'roles' => ['SUPER ADMIN']],
                ['name' => 'roles.assign_permissions', 'action' => 'Asignar / quitar permisos a un rol', 'roles' => ['SUPER ADMIN']],
            ],
            'USUARIOS DEL SISTEMA' => [
                ['name' => 'users.view', 'action' => 'Ver listado de usuarios', 'roles' => ['SUPER ADMIN', 'ADMIN']],
                ['name' => 'users.manage_roles', 'action' => 'Asignar / cambiar rol del usuario', 'roles' => ['SUPER ADMIN', 'ADMIN']],
                ['name' => 'users.update_credentials', 'action' => 'Cambiar correo o restablecer contraseña', 'roles' => ['SUPER ADMIN']],
            ],

            'MÓDULO HR (Recursos Humanos)' => [
                // =========================
                // EMPLOYEES
                // =========================
                ['name' => 'hr.employees.view',   'action' => 'Ver listado de empleados',   'roles' => ['SUPER ADMIN', 'ADMIN', 'EMPLEADO']],
                ['name' => 'hr.employees.create', 'action' => 'Crear empleados',    'roles' => ['SUPER ADMIN', 'ADMIN']],
                ['name' => 'hr.employees.edit',   'action' => 'Editar empleados',   'roles' => ['SUPER ADMIN', 'ADMIN']],
                ['name' => 'hr.employees.delete', 'action' => 'Eliminar empleados', 'roles' => ['SUPER ADMIN']],

                // =========================
                // SEDES
                // =========================
                ['name' => 'hr.branches.view',   'action' => 'Ver listado de sedes',   'roles' => ['SUPER ADMIN', 'ADMIN','EMPLEADO']],
                ['name' => 'hr.branches.create', 'action' => 'Crear sedes',            'roles' => ['SUPER ADMIN']],
                ['name' => 'hr.branches.edit',   'action' => 'Editar sedes',           'roles' => ['SUPER ADMIN']],
                ['name' => 'hr.branches.delete', 'action' => 'Eliminar sedes',         'roles' => ['SUPER ADMIN']],


                // =========================
                // ÁREAS
                // =========================
                ['name' => 'hr.departments.view',   'action' => 'Ver listado de áreas',   'roles' => ['SUPER ADMIN', 'ADMIN', 'EMPLEADO']],
                ['name' => 'hr.departments.create', 'action' => 'Crear áreas',            'roles' => ['SUPER ADMIN']],
                ['name' => 'hr.departments.edit',   'action' => 'Editar áreas',           'roles' => ['SUPER ADMIN']],
                ['name' => 'hr.departments.delete', 'action' => 'Eliminar áreas',         'roles' => ['SUPER ADMIN']],
            ],

            'DATOS INTERNOS' => [
                ['name' => 'internal.view_factory_consecutive', 'action' => 'Ver consecutivo [FABRICA-N]', 'roles' => ['SUPER ADMIN', 'ADMIN', 'ASESOR', 'BODEGA']],
                ['name' => 'internal.view_out_consecutive', 'action' => 'Ver consecutivo OUT', 'roles' => ['SUPER ADMIN', 'ADMIN', 'ASESOR', 'BODEGA']],
                ['name' => 'internal.view_dispatcher', 'action' => 'Ver quién despachó / validó', 'roles' => ['SUPER ADMIN', 'ADMIN', 'ASESOR', 'BODEGA']],
            ],
        ];

        $rolePermissions = [
            'SUPER ADMIN' => [],
            'ADMIN' => [],
            'EMPLEADO' => [],
            'ASESOR' => [],
            'BODEGA' => [],
            'CLIENTE' => [],
            'CLI. DIST.' => [],
        ];

        $this->info('⚙ Creando permisos y procesando asignaciones...');

        // 4. Crear los permisos en la base de datos
        foreach ($permissionsData as $group => $perms) {
            foreach ($perms as $perm) {
                Permission::create([
                    'name' => $perm['name'],
                    'guard_name' => 'web',
                    'group' => $group,
                    'action' => $perm['action'],
                    'description' => 'Permite ' . strtolower($perm['action']),
                ]);

                foreach ($perm['roles'] as $roleName) {
                    $rolePermissions[$roleName][] = $perm['name'];
                }
            }
        }

        $this->info('✔ Permisos creados en la base de datos.');

        // 5. Garantizar la existencia de los roles y sincronizar permisos
        $this->info('Verificando roles y sincronizando permisos...');

        foreach ($rolePermissions as $roleName => $assignedPermissions) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web'
            ]);

            $role->syncPermissions($assignedPermissions);
        }

        $this->info('✔ Permisos asignados a sus respectivos roles.');
        $this->info('✔ Configuración completada correctamente. Todo listo.');

        return self::SUCCESS;
    }
}

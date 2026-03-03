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

            'MÓDULO ER (ERRORES)' => [
                ['name' => 'er.dashboard', 'action' => 'Dashboard principal', 'roles' => ['SUPER ADMIN', 'ADMIN', 'ASESOR', 'BODEGA']],
                ['name' => 'er.mine', 'action' => 'MIS ER (mis errores)', 'roles' => ['SUPER ADMIN', 'ADMIN', 'ASESOR', 'BODEGA']],
                ['name' => 'er.register_warranty', 'action' => 'Registrar solicitud de garantía', 'roles' => ['SUPER ADMIN', 'ADMIN', 'CLIENTE', 'CLI. DIST.']],
                ['name' => 'er.upload_evidence', 'action' => 'Subir evidencias', 'roles' => ['SUPER ADMIN', 'ADMIN', 'CLIENTE', 'CLI. DIST.']],
                ['name' => 'er.view_own_requests', 'action' => 'Ver sus propias solicitudes', 'roles' => ['SUPER ADMIN', 'ADMIN', 'ASESOR', 'BODEGA', 'CLIENTE', 'CLI. DIST.']],
                ['name' => 'er.view_status_notes', 'action' => 'Ver estado + notas del asesor', 'roles' => ['SUPER ADMIN', 'ADMIN', 'ASESOR', 'BODEGA', 'CLIENTE', 'CLI. DIST.']],
                ['name' => 'er.view_tracking', 'action' => 'Ver Nº guía Servientrega', 'roles' => ['SUPER ADMIN', 'ADMIN', 'ASESOR', 'BODEGA', 'CLIENTE', 'CLI. DIST.']],
                ['name' => 'er.view_pending', 'action' => 'Ver solicitudes pendientes', 'roles' => ['SUPER ADMIN', 'ADMIN', 'ASESOR']],
                ['name' => 'er.evaluate', 'action' => 'Evaluar solicitudes', 'roles' => ['SUPER ADMIN', 'ADMIN', 'ASESOR']],
                ['name' => 'er.assign_factory', 'action' => 'Asignar fábrica (YAN/LEOMA/CANDY)', 'roles' => ['SUPER ADMIN', 'ADMIN', 'ASESOR']],
                ['name' => 'er.group_requests', 'action' => 'Agrupar solicitudes', 'roles' => ['SUPER ADMIN', 'ADMIN', 'ASESOR']],
                ['name' => 'er.send_to_warehouse', 'action' => 'Enviar grupos a bodega', 'roles' => ['SUPER ADMIN', 'ADMIN', 'ASESOR']],
                ['name' => 'er.receive_dispatch', 'action' => 'Recibir grupos de despacho', 'roles' => ['SUPER ADMIN', 'ADMIN', 'BODEGA']],
                ['name' => 'er.enter_tracking', 'action' => 'Ingresar guía Servientrega', 'roles' => ['SUPER ADMIN', 'ADMIN', 'BODEGA']],
                ['name' => 'er.enter_out_consecutive', 'action' => 'Ingresar consecutivo OUT', 'roles' => ['SUPER ADMIN', 'ADMIN', 'BODEGA']],
                ['name' => 'er.mark_dispatched', 'action' => 'Marcar como Despachada', 'roles' => ['SUPER ADMIN', 'ADMIN', 'BODEGA']],
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

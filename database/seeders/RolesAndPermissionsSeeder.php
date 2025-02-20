<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Schema;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar la caché de roles y permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos para cada módulo
        $permissions = [
            // Permisos para usuarios
            'ver usuarios',
            'crear usuarios',
            'editar usuarios',
            'eliminar usuarios',
            
            // Permisos para roles
            'ver roles',
            'crear roles',
            'editar roles',
            'eliminar roles',
            
            // Permisos para dueños de gimnasios
            'ver dueños',
            'crear dueños',
            'editar dueños',
            'eliminar dueños',
            
            // Permisos para membresías
            'ver membresias',
            'crear membresias',
            'editar membresias',
            'eliminar membresias',
            
            // Permisos para rutinas
            'ver rutinas',
            'crear rutinas',
            'editar rutinas',
            'eliminar rutinas',
            
            // Permisos para asistencias
            'ver asistencias',
            'registrar asistencias',
            
            // Permisos para nutrición
            'ver nutricion',
            'crear nutricion',
            'editar nutricion',
            'eliminar nutricion'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear rol de administrador y asignar todos los permisos
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Crear otros roles con permisos específicos
        $entrenadorRole = Role::create(['name' => 'entrenador']);
        $entrenadorRole->givePermissionTo([
            'ver usuarios',
            'ver rutinas',
            'crear rutinas',
            'editar rutinas',
            'ver asistencias',
            'registrar asistencias',
            'ver nutricion',
            'crear nutricion',
            'editar nutricion'
        ]);

        $duenoRole = Role::create(['name' => 'dueño']);
        $duenoRole->givePermissionTo([
            'ver usuarios',
            'crear usuarios',
            'editar usuarios',
            'eliminar usuarios',
            'ver membresias',
            'crear membresias',
            'editar membresias',
            'eliminar membresias',
            'ver rutinas',
            'crear rutinas',
            'editar rutinas',
            'eliminar rutinas',
            'ver asistencias',
            'registrar asistencias',
            'ver nutricion',
            'crear nutricion',
            'editar nutricion',
            'eliminar nutricion'
        ]);

        $clienteRole = Role::create(['name' => 'cliente']);
        $clienteRole->givePermissionTo([
            'ver rutinas',
            'ver asistencias'
        ]);
    }
}

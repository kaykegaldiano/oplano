<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $perms = [
            'students.view', 'students.create', 'students.update', 'students.delete',
            'classes.view', 'classes.create', 'classes.update', 'classes.delete',
            'enrollments.view', 'enrollments.create', 'enrollments.update', 'enrollments.delete',
            'enrollments.complete',
            'observations.view', 'observations.create', 'observations.update', 'observations.delete',
        ];

        foreach ($perms as $perm) {
            Permission::findOrCreate($perm);
        }

        $admin = Role::findOrCreate('admin_global');
        $cs = Role::findOrCreate('customer_success');
        $monitor = Role::findOrCreate('monitor');

        $admin->givePermissionTo($perms);

        $cs->givePermissionTo([
            'students.view', 'students.create', 'students.update',
            'classes.view', 'classes.create', 'classes.update',
            'enrollments.view', 'enrollments.create', 'enrollments.update',
            'observations.view', 'observations.create', 'observations.update', 'observations.delete',
        ]);

        $monitor->givePermissionTo([
            'classes.view',
            'students.view',
            'enrollments.view', 'enrollments.complete',
            'observations.view', 'observations.create', 'observations.update', 'observations.delete',
        ]);

        $uAdmin = User::where('email', 'admin@test.com')->first();
        $uCs = User::where('email', 'cs@test.com')->first();
        $uMonitor = User::where('email', 'monitor@test.com')->first();

        $uAdmin?->assignRole($admin);
        $uCs?->assignRole($cs);
        $uMonitor?->assignRole($monitor);
    }
}

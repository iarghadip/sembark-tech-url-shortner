<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // permissions list according to the requirement

        $permissions = [
            'can-send-invite',
            'can-accept-invite',
            'can-short-url',
            'can-see-all-data',
            'can-see-com-data',
            'can-see-self-data',
        ];

        // added the web guard
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // role and super admin account creation

        $superAdminRole = Role::create(['name' => 'SuperAdmin']);
        $adminRole = Role::create(['name' => 'Admin']);
        $memberRole = Role::create(['name' => 'Member']);

        $superAdminRole->givePermissionTo([
            'can-send-invite',
            'can-see-com-data',
            'can-see-all-data'
        ]);

        $adminRole->givePermissionTo([
            'can-send-invite',
            'can-accept-invite',
            'can-short-url',
            'can-see-com-data',
            'can-see-self-data'
        ]);

        $memberRole->givePermissionTo([
            'can-accept-invite',
            'can-short-url',
            'can-see-self-data'
        ]);

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'super@sembark.com',
            'password' => 'sembark@123',
        ]);
        
        $superAdmin->assignRole($superAdminRole);
    }
}

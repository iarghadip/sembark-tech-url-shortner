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
            'can-invite',
            'can-short-url',
            'can-see-all-data',
            'can-see-com-data',
            'can-see-self-data',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // role and super admin account creation

        $superAdminRole = Role::create(['name' => 'SuperAdmin']);
        $adminRole = Role::create(['name' => 'Admin']);
        $memberRole = Role::create(['name' => 'Member']);

        $superAdminRole->givePermissionTo(['can-invite', 'can-see-all-data']);

        $adminRole->givePermissionTo(['can-short-url', 'can-see-com-data', 'can-see-self-data']);

        $memberRole->givePermissionTo(['can-short-url', 'can-see-self-data']);

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'super@sembark.com',
            'password' => bcrypt('sembark@123'),
        ]);
        
        $superAdmin->assignRole($superAdminRole);
    }
}

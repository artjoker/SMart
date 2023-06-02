<?php

namespace Database\Seeders;

use App\Entity\Admin\RoleAdminPanel;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

/**
 * Class RolesSeeder.
 */
class RolesSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @throws \Throwable
     * @return void
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        DB::transaction(static function () {
            // add permissions here
            Permission::create(['name' => 'base']);
        });

        //creating admin role
        $role = Role::create([
            'name'   => RoleAdminPanel::ADMIN,
            'active' => true,
        ]);
        $role->syncPermissions(Permission::all());

        //creating manager role
        $role = Role::create([
            'name'   => RoleAdminPanel::MANAGER,
            'active' => true,
        ]);
        $role->syncPermissions(Permission::whereIn(
            'name',
            [
                //put admin permissions here
            ]
        )->get());
    }
}

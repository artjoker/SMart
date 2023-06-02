<?php

namespace Database\Seeders;

use App\Entity\Admin\RoleAdminPanel;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Class UsersSeeder.
 */
class UsersSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run(): void
    {
        //creating default admin user
        $admin = User::factory()->createOne([
            'name'     => 'Admin',
            'password' => bcrypt('secret'),
            'email'    => 'admin@example.com',
            'active'   => true,
        ]);

        $role = Role::where('name', RoleAdminPanel::ADMIN)->first();
        $admin->assignRole($role);

        //creating default manager user
        $manager = User::factory()->createOne([
            'name'     => 'Manager',
            'password' => bcrypt('secret'),
            'email'    => 'manager@example.com',
            'active'   => true,
        ]);

        $role = Role::where('name', RoleAdminPanel::MANAGER)->first();
        $manager->assignRole($role);
    }
}

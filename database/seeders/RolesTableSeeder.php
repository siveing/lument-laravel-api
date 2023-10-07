<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create role_admin
        $role_admin = new Role();
        $role_admin->name = "Role Admin";
        $role_admin->slug = "ROLE_ADMIN";
        $role_admin->save();
        $role_admin->permissions()->attach(Permission::where('slug', 'SHOW_USER')->first());
        $role_admin->permissions()->attach(Permission::where('slug', 'DESTROY_USER')->first());

        // Create role_user
        $role_user = new Role();
        $role_user->name = "Role User";
        $role_user->slug = "ROLE_USER";
        $role_user->save();
        $role_user->permissions()->attach(Permission::where('slug', 'SHOW_USER')->first());
        $role_user->permissions()->attach(Permission::where('slug', 'DESTROY_USER')->first());
        $role_user->permissions()->attach(Permission::where('slug', 'UPDATE_USER')->first());
        $role_user->permissions()->attach(Permission::where('slug', 'SHOW_USER_PROFILE')->first());
        $role_user->permissions()->attach(Permission::where('slug', 'SHOW_WALLET')->first());

        $role_user->permissions()->attach(Permission::where('slug', 'SHOW_TRANSACTION')->first());
        $role_user->permissions()->attach(Permission::where('slug', 'UPDATE_TRANSACTION')->first());
        $role_user->permissions()->attach(Permission::where('slug', 'DESTROY_TRANSACTION')->first());
        $role_user->permissions()->attach(Permission::where('slug', 'STORE_TRANSACTION')->first());
    }
}

<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_admin = Role::where('slug', 'ROLE_ADMIN')->first();
        
        // create admin
        $admin = new User();
        $admin->name = 'Admin';
        $admin->email = 'admin@cute.com';
        $admin->password = Hash::make('jincute');
        $admin->save();
        $admin->roles()->attach($role_admin);
    }
}

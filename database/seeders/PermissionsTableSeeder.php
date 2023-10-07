<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            // Permission transactions
            [
                'name' => 'Store Transaction',
                'slug' => 'STORE_TRANSACTION',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'name' => 'Update Transaction',
                'slug' => 'UPDATE_TRANSACTION',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'name' => 'Show Transaction',
                'slug' => 'SHOW_TRANSACTION',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'name' => 'Destroy Transaction',
                'slug' => 'DESTROY_TRANSACTION',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Permissions wallets
            [
                'name' => 'Store Wallet',
                'slug' => 'STORE_WALLET',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'name' => 'Update Wallet',
                'slug' => 'UPDATE_WALLET',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'name' => 'Show Wallet',
                'slug' => 'SHOW_WALLET',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'name' => 'Destroy Wallet',
                'slug' => 'DESTROY_WALLET',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Permissions users
            [
                'name' => 'Store User',
                'slug' => 'STORE_USER',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'name' => 'Update User',
                'slug' => 'UPDATE_USER',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'name' => 'Show User',
                'slug' => 'SHOW_USER',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'name' => 'Destroy User',
                'slug' => 'DESTROY_USER',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'name' => 'Show User Profile',
                'slug' => 'SHOW_USER_PROFILE',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

    }
}

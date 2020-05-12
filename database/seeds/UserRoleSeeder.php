<?php

use Illuminate\Database\Seeder;
use App\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'Administrator',
            'description' => 'Super User'
        ]);

        Role::create([
            'name' => 'User',
            'description' => 'Can Add Expenses'
        ]);
    }
}

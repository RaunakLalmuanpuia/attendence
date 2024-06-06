<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        User::query()->upsert([
            ['id'=>1,'full_name'=>'admin','mobile'=>'0000000001','password'=>Hash::make('password')],
            ['id'=>2,'full_name'=>'manager','mobile'=>'0000000002','password'=>Hash::make('password')],
        ], ['id']);

        Role::query()->upsert([
            ['name'=>'Admin','guard_name'=>'web'],
            ['name'=>'Manager','guard_name'=>'web'],
        ], ['name']);

        $admin=User::query()->find(1);
        $admin->assignRole('Admin');

        $manager=User::query()->find(2);
        $manager->assignRole('Manager');


    }
}

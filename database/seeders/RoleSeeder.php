<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        Role::firstOrCreate(["name"=>"Admin"]);
        Role::firstOrCreate(["name"=>"Asset Owner"]);
        Role::firstOrCreate(["name"=>"User"]);

        Permission::firstOrCreate(['name'=>'Gitlab Asset Owner']);
        Permission::firstOrCreate(["name"=>'AWS Asset Owner']);
    }
}

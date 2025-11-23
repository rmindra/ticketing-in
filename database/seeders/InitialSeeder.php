<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class InitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rAdmin = Role::firstOrCreate(['role' => 'admin']);
        $rUser = Role::firstOrCreate(['role' => 'user']);

        $it = Department::firstOrCreate(['name' => 'IT']);
        $hr = Department::firstOrCreate(['name' => 'HR']);

        User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role_id' => $rAdmin->id,
                'department_id' => $it->id
            ]
        );

        Category::firstOrCreate(['name' => 'Printer']);
        Category::firstOrCreate(['name' => 'Network']);
    }
}

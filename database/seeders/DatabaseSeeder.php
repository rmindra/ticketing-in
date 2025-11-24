<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Run InitialSeeder to seed roles, departments, admin and users
        $this->call(InitialSeeder::class);

        // Example test user (assign role and department)
        $role = Role::firstOrCreate(['role' => 'user']);
        $dept = Department::firstOrCreate(['name' => 'IT']);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role_id' => $role->id,
            'department_id' => $dept->id,
        ]);
    }
}

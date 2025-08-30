<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create(['name' => 'Admin', 'email' => 'admin@test.com', 'role' => 'admin_global', 'password' => bcrypt('password')]);
        User::factory()->create(['name' => 'CS', 'email' => 'cs@test.com', 'role' => 'customer_success', 'password' => bcrypt('password')]);
        User::factory()->create(['name' => 'Monitor', 'email' => 'monitor@test.com', 'role' => 'monitor', 'password' => bcrypt('password')]);

        $students = Student::factory()->count(30)->create();
        $classes = ClassModel::factory()->count(6)->create();

        foreach ($classes as $class) {
            foreach ($students->random(12) as $st) {
                Enrollment::query()->firstOrCreate([
                    'student_id' => $st->id,
                    'class_id' => $class->id
                ], ['status' => 'active']);
            }
        }
    }
}

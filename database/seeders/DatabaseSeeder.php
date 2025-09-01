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
        User::factory()->create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => bcrypt('password')]);
        User::factory()->create(['name' => 'CS', 'email' => 'cs@test.com', 'password' => bcrypt('password')]);
        User::factory()->create(['name' => 'Monitor', 'email' => 'monitor@test.com', 'password' => bcrypt('password')]);

        $this->call(PermissionSeeder::class);

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

        $monitor = User::role('monitor')->first();

        if ($monitor) {
            $classesToMonitor = ClassModel::inRandomOrder()->take(3)->get();

            foreach ($classesToMonitor as $class) {
                $class->monitors()->syncWithoutDetaching([
                    $monitor->id => ['role_in_class' => 'monitor']
                ]);
            }
        }
    }
}

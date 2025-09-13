<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Reviewer',
                'slug' => 'reviewer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Candidate',
                'slug' => 'candidate',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}

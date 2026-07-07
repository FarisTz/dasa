<?php

namespace Database\Seeders;

use App\Models\User;
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

        $user =[[
            'name' => 'Admin User',
            'email' => 'admin@eg.com',
            'role' => 'admin',
            'password' => bcrypt('12345678'),
            'status' => 'active',
            'email_verified_at' => now(),
        ],
        [
            'name' => 'Applicant User',
            'email' => 'applicant@eg.com',
            'role' => 'applicant',
            'password' => bcrypt('12345678'),
            'status' => 'active',
            'email_verified_at' => now(),
        ],
        [
            'name' => 'Coordinator User',
            'email' => 'coordinator@eg.com',
            'role' => 'coordinator',
            'password' => bcrypt('12345678'),
            'status' => 'active',
            'email_verified_at' => now(),
        ],
        [
            'name' => 'Beneficiary User',
            'email' => 'beneficiary@eg.com',
            'role' => 'beneficiary',
            'password' => bcrypt('12345678'),
            'status' => 'active',
            'email_verified_at' => now(),
        ],
        [
            'name' => 'Beneficiary User 1',
            'email' => 'beneficiary1@eg.com',
            'role' => 'beneficiary',
            'password' => bcrypt('12345678'),
            'status' => 'active',
            'email_verified_at' => now(),
        ],
        [
            'name' => 'Beneficiary User 2',
            'email' => 'beneficiary2@eg.com',
            'role' => 'beneficiary',
            'password' => bcrypt('12345678'),
            'status' => 'active',
            'email_verified_at' => now(),
        ],
        [
            'name' => 'Beneficiary User 3',
            'email' => 'beneficiary3@eg.com',
            'role' => 'beneficiary',
            'password' => bcrypt('12345678'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]
        ,[
            'name' => 'Beneficiary User 4',
            'email' => 'beneficiary4@eg.com',
            'role' => 'beneficiary',
            'password' => bcrypt('12345678'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]
        ,[
            'name' => 'Beneficiary User 5',
            'email' => 'beneficiary5@eg.com',
            'role' => 'beneficiary',
            'password' => bcrypt('12345678'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]
        ,[
            'name' => 'Beneficiary User 6',
            'email' => 'beneficiary6@eg.com',
            'role' => 'beneficiary',
            'password' => bcrypt('12345678'),
            'status' => 'active',
            'email_verified_at' => now(),
        ],
        [
            'name' => 'Beneficiary User 7',
            'email' => 'beneficiary7@eg.com',
            'role' => 'beneficiary',
            'password' => bcrypt('12345678'),
            'status' => 'active',
            'email_verified_at' => now(),
        ],
        [
            'name' => 'Beneficiary User 8',
            'email' => 'beneficiary8@eg.com',
            'role' => 'beneficiary',
            'password' => bcrypt('12345678'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]
    ];

        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}

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
        User::create([
            'name' => 'admin warung',
            'email' => 'admin@warung.com',
            'role' => 'admin',
            'password' => bcrypt('warung212'),
        ]);
        
        User::create([
            'name' => 'Faisal kasir',
            'email' => 'faisal@warung.com',
            'role' => 'staff',
            'password' => bcrypt('warung212'),
        ]);
        
        //to make seeding instant with 10 data (if u wanna add more data just change fucntion factory() to whatever you want to add) #kiw
        
        // User::factory(10)->create();
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}

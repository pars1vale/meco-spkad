<?php

namespace Database\Seeders;

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
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // super admin account
        User::create([
            'name' => 'it',
            'email' => 'it@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        $this->call(DataSatuanSeeder::class);
        $this->call(KelompokStandartHargaSeeder::class);
        $this->call(PangkatSeeder::class);
    }
}

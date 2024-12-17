<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('11221122'),
        ]);

        $kategori = [[
            'uuid' =>  Uuid::uuid4()->toString(),
            'nama' => 'ATM',
        ],[
            'uuid' =>  Uuid::uuid4()->toString(),
            'nama' => 'Buku Tabungan'
        ]];

        DB::table('kategoris')->insert($kategori);

    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Factories\UserFactory;
use Database\Factories\InstrumentFactory;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

//         User::factory()->create([
//             'name' => 'Test User',
//             'email' => 'test@example.com',
//         ]);

//        User::factory()->create([
//            'name' => 'Chris Nowlan',
//            'email' => 'chrisnowlan321@gmail.com',
//            'password' => '$2y$10$WsQg/YFGixkwLfJf4O8Lk.6o0ece8Meo9vFHdImNm5S0GxBCsya8C',
//        ]);
//        User::factory()->create([
//            'name' => 'Cindy Rudd',
//            'email' => 'cinboop@gmail.com',
//            'password' => '$2y$10$jXE5E1lf6iBm9YgiRqTWe.RJlxQ9Tu4PECGY/Ces1t/TU3hJKGWGi',
//        ]);
//        User::factory()->create([
//            'name' => 'Muhammad',
//            'email' => 'muhammadubaidn@gmailcom',
//            'password' => '$2y$10$KNlWBmK.lp6LZiKpTctQw.aXLu7A0x.ev/uv/omCKa5NMWPvlK4ri',
//        ]);
//        User::factory()->create([
//            'name' => 'Barbara Himel',
//            'email' => 'himelstx@gmail.com',
//            'password' => '$2y$10$BiAnLYMCF/DghqpbNRTc7uhZ12GOUUbFTG//EOzdD/RXcDoLJ44OW',
//        ]);
//        User::factory()->create([
//            'name' => 'Robert Himel',
//            'email' => 'testingencompass@gmail.com',
//            'password' => '$2y$10$Sio8rm3Vc6fNOtjv3.toEeypGTed4sADpnmiVVj0zCJliKZGRAGVW',
//        ]);



        User::factory(10)->create();

        $this->call([
            InstrumentSeeder::class,
        ]);
    }
}

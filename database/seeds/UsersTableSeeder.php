<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin Kejar Bahasa',
            'email' => 'admin@kejarbahasa.id',
            'password' => bcrypt('12345678')
        ]);
    }
}

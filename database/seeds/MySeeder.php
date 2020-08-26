<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class MySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'max',
            'last_name' => 'herrera',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
        ]);
    }
}

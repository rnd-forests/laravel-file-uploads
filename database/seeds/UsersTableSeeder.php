<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'name' => 'Vinh Nguyen',
            'username' => 'vinhnguyen',
            'email' => 'vinhnguyen@nowhere.com',
        ]);
    }
}

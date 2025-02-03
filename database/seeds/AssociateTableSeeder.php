<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Associate;

class AssociateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Associate';
        $user->email = 'associate@jkshahonline.com';
        $user->role = 7;
        $user->password = Hash::make('secret');
        $user->save();

        $associate = new Associate();
        $associate->user_id = $user->id;
        $associate->email = 'associate@jkshahonline.com';
        $associate->phone = '1234567890';
        $associate->address = 'India';
        $associate->save();
    }
}

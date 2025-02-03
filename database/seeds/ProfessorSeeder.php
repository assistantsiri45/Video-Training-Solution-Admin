<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Professor;
use Illuminate\Support\Facades\Hash;

class ProfessorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Professor';
        $user->email = 'professor@jkshahonline.com';
        $user->phone = 1234567890;
        $user->role = 6;
        $user->password = Hash::make('secret');
        $user->save();

        $professor = new Professor();
        $professor->user_id = $user->id;
        $professor->name = $user->name;
        $professor->email = $user->email;
        $professor->mobile = $user->phone;
        $professor->title = 'Professor';
        $professor->save();
    }
}

<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class StudentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Student';
        $user->email = 'student@jkshahonline.com';
        $user->role = 5;
        $user->password = Hash::make('secret');
        $user->save();

        $student = new Student();
        $student->user_id = $user->id;
        $student->name = 'Student';
        $student->email = 'student@jkshahonline.com';
        $student->phone = '1234567890';
        $student->age = 22;
        $student->address = 'Mumbai';
        $student->country_id = 1;
        $student->state_id = 1;
        $student->city = 'Mumbai';
        $student->pin = '400072';
        $student->course_id = '2';
        $student->level_id = '1';
        $student->attempt_year = '2020';
        $student->save();
    }
}

<?php

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Language::query()->truncate();
        Language::query()->create(['name' => 'English']);
        Language::query()->create(['name' => 'Hindi']);
        Language::query()->create(['name' => 'English + Hindi']);
    }
}

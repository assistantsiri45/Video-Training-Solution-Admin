<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $update_settings = Setting::updateOrCreate(['label' => 'Pendrive Price', 'key' => 'pendrive_price','value'=>499]);
        $update_settings = Setting::updateOrCreate(['label' => 'Associate Commission (%)', 'key' => 'associate_commission','value'=>10]);
        $update_settings = Setting::updateOrCreate(['label' => 'Professor Revenue (%)', 'key' => 'professor_revenue','value'=>10]);
        $update_settings = Setting::updateOrCreate(['label' => 'CGST (%)', 'key' => 'cgst','value'=>9]);
        $update_settings = Setting::updateOrCreate(['label' => 'IGST (%)', 'key' => 'igst','value'=>9]);
        $update_settings = Setting::updateOrCreate(['label' => 'SGST (%)', 'key' => 'sgst','value'=>9]);
        $update_settings = Setting::updateOrCreate(['label' => 'GSTN', 'key' => 'gstn','value'=>12345678]);

        $update_settings->save();
    }
}

<?php

use Illuminate\Database\Seeder;
USE App\Models\JMoneySetting;

class JmoneySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = new JMoneySetting();
        $settings->sign_up_point = 10;
        $settings->sign_up_point_expiry = 10;
        $settings->first_purchase_point = 10;
        $settings->first_purchase_point_expiry = 10;
        $settings->promotional_activity_point = 10;
        $settings->promotional_activity_point_expiry = 10;
        $settings->referral_activity_point = 10;
        $settings->referral_activity_point_expiry = 10;
        $settings->refund_expiry = 10;
        $settings->save();
    }
}

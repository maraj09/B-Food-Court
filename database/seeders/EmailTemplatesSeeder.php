<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Welcome',
                'trigger' => 'welcome',
                'subject' => 'Welcome to Our Service!',
                'body' => 'Hello, and welcome to our service! We are excited to have you.',
            ],
            [
                'name' => 'Order Placed',
                'trigger' => 'order_placed',
                'subject' => 'Your Order Has Been Placed',
                'body' => 'Thank you for your order. We are currently processing it.',
            ],
            [
                'name' => 'Order Invoice',
                'trigger' => 'order_invoice',
                'subject' => 'Your Order Invoice',
                'body' => 'Please find your order invoice attached.',
            ],
            [
                'name' => 'Coupon',
                'trigger' => 'coupon',
                'subject' => 'Here’s Your Coupon Code!',
                'body' => 'Use the coupon code provided to get a discount on your next purchase.',
            ],
            [
                'name' => 'Points Credit',
                'trigger' => 'points_credit',
                'subject' => 'Points Added to Your Account',
                'body' => 'You have been credited with points in your account.',
            ],
        ];

        DB::table('email_templates')->insert($templates);
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\AdminWallet;
use App\Models\Customer;
use App\Models\EmailTemplate;
use App\Models\Event;
use App\Models\ExpenseCategory;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\PlayArea;
use App\Models\Point;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorBank;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $roles = [
            [
                'name' => 'admin',
            ],
            [
                'name' => 'vendor',
            ],
            [
                'name' => 'customer',
            ],
        ];
        $items = [
            [
                'vendor_id' => 1,
                'item_name' => 'Demo Item 1',
                'category_id' => 1,
                'price' => 499.00,
                'description' => 'This is a demo item description for Demo Item 1.',
                'item_image' => '/images/demo/food/img-1.jpg',
                'status' => true,
                'approve' => true,
            ],
            [
                'vendor_id' => 1,
                'item_name' => 'Demo Item 2',
                'category_id' => 2,
                'price' => 29.00,
                'description' => 'This is a demo item description for Demo Item 2.',
                'item_image' => '/images/demo/food/img-2.jpg',
                'status' => true,
                'approve' => true,
            ],
            [
                'vendor_id' => 1,
                'item_name' => 'Demo Item 3',
                'category_id' => 1,
                'price' => 14.00,
                'description' => 'This is a demo item description for Demo Item 3.',
                'item_image' => '/images/demo/food/img-3.jpg',
                'status' => true,
                'approve' => true,
            ],
            [
                'vendor_id' => 2,
                'item_name' => 'Demo Item 4',
                'category_id' => 2,
                'price' => 20.00,
                'description' => 'This is a demo item description for Demo Item 4.',
                'item_image' => '/images/demo/food/img-5.jpg',
                'status' => true,
                'approve' => true,
            ],
            [
                'vendor_id' => 2,
                'item_name' => 'Demo Item 5',
                'category_id' => 1,
                'price' => 100.00,
                'description' => 'This is a demo item description for Demo Item 5.',
                'item_image' => '/images/demo/food/img-4.jpg',
                'status' => true,
                'approve' => true,
            ],
        ];
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('admin123'),
            ],
            [
                'name' => 'Vendor',
                'phone' => '+8801712345671',
                'password' => bcrypt('123456789'),
            ],
        ];
        $expenseCategories = [
            [
                'name' => 'Bill',
            ], [
                'name' => 'Salary',
            ], [
                'name' => 'Maintenance',
            ], [
                'name' => 'Event',
            ], [
                'name' => 'Sport',
            ], [
                'name' => 'Others',
            ],
        ];
        foreach ($roles as $key => $value) {
            Role::create($value);
        }
        foreach ($expenseCategories as $key => $value) {
            ExpenseCategory::create($value);
        }
        $index = 0;
        foreach ($users as $key => $value) {
            User::create($value)->assignRole($roles[$index]);
            $index++;
        }
        User::create([
            'name' => 'tv',
            'phone' => '+8801706055611',
            'password' => bcrypt('123456789'),
        ])->assignRole('vendor');

        $vendor = Vendor::create([
            'user_id' => 2,
            'brand_name' => "VENDOR",
            'commission' => 25,
            'fassi_no' => 02,
            'stall_no' => 55,
        ]);
        VendorBank::create([
            'vendor_id' => $vendor->id,
            'balance' => 0,
            'amount_paid' => 0,
        ]);
        $vendor = Vendor::create([
            'user_id' => 3,
            'brand_name' => "BFC",
            'commission' => 10,
            'fassi_no' => 465456,
            'stall_no' => 99,
        ]);
        VendorBank::create([
            'vendor_id' => $vendor->id,
            'balance' => 0,
            'amount_paid' => 0,
        ]);
        Point::create([
            'value' => '1',
            'signup_points' => [
                'points' => 0,
                'alert_message' => 'Alert',
                'status' => 'active'
            ],
            'login_points' => [
                'points' => 0,
                'logins' => 0,
                'limit' => 'day',
                'alert_message' => 'alert',
                'status' => 'active',
            ],
            'order_points' => [
                'points' => 0,
                'alert_message' => 'Alert',
                'status' => 'active'
            ],
            'review_points' => [
                'ratings_points' => 0,
                'review_points' => 0,
                'alert_message' => 'Alert',
                'status' => 'active'
            ],
            'birthday_points' => [
                'points' => 0,
                'alert_message' => 'Alert',
                'status' => 'active'
            ],
        ]);
        ItemCategory::create([
            'name' => 'Sweet'
        ]);
        ItemCategory::create([
            'name' => 'South Indian'
        ]);
        foreach ($items as $item) {
            Item::create($item);
        }
        AdminWallet::create();
        Setting::create();

        $events = [
            [
                'title' => 'Demo Event 1',
                'start_date' => '2024-10-01 10:00:00',
                'end_date' => '2024-10-01 12:00:00',
                'image' => '/images/demo/event/event1.jpg',
                'price' => 100.00,
                'seats' => 50,
                'details' => 'This is the first Demo Event.',
            ],
            [
                'title' => 'Demo Event 2',
                'start_date' => '2024-11-05 14:00:00',
                'end_date' => '2024-11-05 19:00:00',
                'image' => '/images/demo/event/event2.jpg',
                'price' => 150.00,
                'seats' => 75,
                'details' => 'This is the second Demo Event.',
            ],
            [
                'title' => 'Demo Event 3',
                'start_date' => '2024-09-10 09:00:00',
                'end_date' => '2024-09-10 12:00:00',
                'image' => '/images/demo/event/event3.jpg',
                'price' => 200.00,
                'seats' => 100,
                'details' => 'This is the third Demo Event.',
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }

        $playAreas = [
            [
                'title' => 'Demo Play Area 1',
                'image' => '/images/demo/play-area/playArea1.jpg',
                'details' => 'This is the first Demo Play Area.',
                'price' => 100,
                'security_deposit' => 50,
                'max_duration' => 2,
                'max_player' => 10,
            ],
            [
                'title' => 'Demo Play Area 2',
                'image' => '/images/demo/play-area/playArea2.jpg',
                'details' => 'This is the second Demo Play Area.',
                'price' => 200,
                'security_deposit' => 100,
                'max_duration' => 3,
                'max_player' => 15,
            ],
            [
                'title' => 'Demo Play Area 3',
                'image' => '/images/demo/play-area/playArea3.jpg',
                'details' => 'This is the third Demo Play Area.',
                'price' => 500,
                'security_deposit' => 300,
                'max_duration' => 5,
                'max_player' => 22,
            ],
        ];

        foreach ($playAreas as $playArea) {
            PlayArea::create($playArea);
        }
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

        foreach ($templates as $template) {
            EmailTemplate::create($template);
        }
    }
}

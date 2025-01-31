<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'dashboard-states',
            'dashboard-information',
            'dashboard-tickets-management',
            'dashboard-latest',
            'user-management',
            'coupons-management',
            'orders-management',
            'customers-management',
            'place-new-order-drawer',
            'vendors-management',
            'items-management',
            'play-areas-management',
            'events-management',
            'points-management',
            'notifications-management',
            'billing-clients-management',
            'payouts-management',
            'expenses-management',
            'reports-management',
            'app-settings-management',
            'cashier-items-management',
            'cashier-events-management',
            'cashier-play-area-management',
            'dashboard-pricing-information',
            'cashier-create-customer',
            'cashier-view-orders',
        ];

        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        }
    }
}

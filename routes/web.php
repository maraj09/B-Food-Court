<?php

use App\Events\OrderStatusChangeEvent;
use App\Http\Controllers\Admin\AppController;
use App\Http\Controllers\Admin\BillingCategoryController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CouponsController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\InvoiceTaxController;
use App\Http\Controllers\Admin\ItemCategoryController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Vendor\ItemsController as VendorItemsController;
use App\Http\Controllers\Vendor\OrdersController as VendorOrdersController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\PayoutsController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PlayAreaController;
use App\Http\Controllers\Admin\PointController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Customer\AppController as CustomerAppController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\TicketController as CustomerTicketController;
use App\Http\Controllers\Guest\AppController as GuestAppController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Vendor\AppController as VendorAppController;
use App\Http\Controllers\Vendor\CouponsController as VendorCouponsController;
use App\Http\Controllers\Vendor\PayoutsController as VendorPayoutsController;
use App\Mail\CouponEmail;
use App\Mail\PointsCreditEmail;
use App\Mail\WelcomeEmail;
use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/migrate', function () {
    Artisan::call('migrate');
    return 'DONE';
});

Route::get('/permission-seed', function () {
    Artisan::call('db:seed', [
        '--class' => 'PermissionsTableSeeder',
    ]);
    return 'DONE';
});

Route::get('/email-templates-seed', function () {
    Artisan::call('db:seed', [
        '--class' => 'EmailTemplatesSeeder',
    ]);
    return 'DONE';
});

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'DONE';
});

Route::middleware('guest')->get('/', function () {
    return view('auth.login');
});

Route::prefix('/dashboard')->middleware(['auth'])->group(function () {
    Route::get('/', [AppController::class, 'index']);
    Route::get('/profile', [AppController::class, 'profileIndex']);
    Route::put('/profile', [AppController::class, 'store'])->name('admin.profile.update');

    Route::middleware(['permission:vendors-management'])->group(function () {
        Route::get('/vendors', [VendorController::class, 'index']);
        Route::get('/vendors/{user}', [VendorController::class, 'show']);
        Route::post('/vendor/register', [VendorController::class, 'store']);
        Route::get('/vendors/{user}/edit', [VendorController::class, 'edit']);
        Route::put('/vendors/{id}', [VendorController::class, 'update']);
        Route::get('/get-order-data-for-vendor', [OrdersController::class, 'getOrderDrawerForVendor']);
        Route::delete('/vendors/{user}/delete', [VendorController::class, 'destroy']);
        Route::post('/update-vendor-status/{id}', [VendorController::class, 'updateApprove']);
        Route::delete('/{vendorId}/remove-document', [VendorController::class, 'removeDocument']);
    });

    Route::middleware(['permission:customers-management'])->group(function () {
        Route::get('/customers', [CustomerController::class, 'index']);
        Route::get('/customers/{user}', [CustomerController::class, 'show']);
        Route::post('/customers/register', [CustomerController::class, 'store']);
        Route::get('/customers/{user}/edit', [CustomerController::class, 'edit']);
        Route::post('/customers/{user}/edit-points', [CustomerController::class, 'editPoints']);
        Route::put('/customers/{id}', [CustomerController::class, 'update']);
        Route::post('/customers/{id}/updateWithPoints', [CustomerController::class, 'updateWithPoints'])->name('update.customerWithPoints');
        Route::delete('/customers/{user}/delete', [CustomerController::class, 'destroy']);
        Route::get('/get-customer-data', [CustomerController::class, 'getCustomerDrawer']);
    });

    Route::middleware(['permission:customers-management|cashier-create-customer'])->group(function () {
        Route::post('/customers/register', [CustomerController::class, 'store']);
    });

    Route::middleware(['permission:items-management'])->group(function () {
        Route::get('/items', [ItemController::class, 'index']);
        Route::post('/items/store', [ItemController::class, 'store']);
        Route::post('/update-item-status/{id}', [ItemController::class, 'updateApprove']);
        Route::get('/items/{item}', [ItemController::class, 'show']);
        Route::put('/items/{id}', [ItemController::class, 'update']);
        Route::delete('/items/{id}/delete', [ItemController::class, 'destroy']);
        Route::resource('categories', ItemCategoryController::class);
    });

    Route::middleware(['permission:points-management'])->group(function () {
        Route::get('/points', [PointController::class, 'index']);
        Route::put('/points/update-value', [PointController::class, 'updateValue'])->name('points.updateValue');
        Route::put('/points/update-minimum-amount', [PointController::class, 'updateMinimumAmount'])->name('points.updateMinimumAmount');
        Route::put('/points/update-max-point', [PointController::class, 'updateMaxPoint'])->name('points.updateMaxPoint');
        Route::post('/points/update-signup-points', [PointController::class, 'updateSignupPoints'])->name('points.updateSignupPoints');
        Route::post('/points/update-signup-status', [PointController::class, 'updateSignupStatus'])->name('points.updateSignupStatus');
        Route::post('points/updateLoginPoints', [PointController::class, 'updateLoginPoints'])->name('points.updateLoginPoints');
        Route::post('points/updateLoginStatus', [PointController::class, 'updateLoginStatus'])->name('points.updateLoginStatus');

        Route::post('/points/updateOrdersPoints', [PointController::class, 'updateOrderPoints'])->name('points.updateOrdersPoints');
        Route::post('/points/updateOrdersStatus', [PointController::class, 'updateOrderStatus'])->name('points.updateOrdersStatus');

        Route::post('/points/updatePlayAreaPoints', [PointController::class, 'updatePlayAreaPoints'])->name('points.updatePlayAreaPoints');
        Route::post('/points/updatePlayAreaStatus', [PointController::class, 'updatePlayAreaStatus'])->name('points.updatePlayAreaStatus');

        Route::post('/points/updateEventPoints', [PointController::class, 'updateEventPoints'])->name('points.updateEventPoints');
        Route::post('/points/updateEventStatus', [PointController::class, 'updateEventStatus'])->name('points.updateEventStatus');

        Route::post('/points/update-review-ratings-points', [PointController::class, 'updateReviewAndRatingsPoints'])->name('points.updateReviewAndRatingsPoints');
        Route::post('/points/update-review-ratings-status', [PointController::class, 'updateReviewAndRatingsStatus'])->name('points.updateReviewAndRatingsStatus');

        Route::post('/points/update-birthday-points', [PointController::class, 'updateBirthdayPoints'])->name('points.updateBirthdayPoints');
        Route::post('/points/update-birthday-status', [PointController::class, 'updateBirthdayStatus'])->name('points.updateBirthdayStatus');

        Route::get('/point-logs', [PointController::class, 'logIndex']);
        Route::post('/customer-point-logs', [PointController::class, 'logStore'])
            ->name('admin.customer-point-logs.store');
    });

    Route::middleware(['permission:orders-management|place-new-order-drawer|cashier-items-management|cashier-events-management|cashier-play-area-management'])->group(function () {
        Route::get('/orders/create', [OrdersController::class, 'create']);
        Route::post('/orders/store', [OrdersController::class, 'store']);
        Route::post('/apply-coupon', [OrdersController::class, 'applyCoupon']);
    });

    Route::middleware(['permission:orders-management|cashier-view-orders'])->group(function () {
        Route::get('/orders', [OrdersController::class, 'index']);
        Route::get('/get-order-data', [OrdersController::class, 'getOrderDrawer']);
        Route::get('/orders/{order}', [OrdersController::class, 'show']);
        Route::post('/orders/search', [OrdersController::class, 'search'])->name('admin.orders.search');
        Route::put('/order-items/update-status', [OrdersController::class, 'updateItemStatus'])->name('admin.orderItems.update-status');
        Route::post('/refund', [OrdersController::class, 'refund'])->name('admin.orders.search');
    });

    Route::middleware(['permission:orders-management'])->group(function () {
        Route::delete('/orders/{id}/delete', [OrdersController::class, 'destroy']);
    });

    Route::middleware(['permission:app-settings-management'])->group(function () {
        Route::get('/settings/general', [SettingsController::class, 'index']);
        Route::get('/settings/notification', [SettingsController::class, 'notificationIndex']);
        Route::get('/settings/footer', [SettingsController::class, 'footerIndex']);
        Route::get('/settings/payment', [SettingsController::class, 'paymentIndex']);
        Route::get('/settings/page', [SettingsController::class, 'pageIndex']);
        Route::get('/settings/modules', [SettingsController::class, 'modulesIndex']);
        Route::get('/settings/authentication', [SettingsController::class, 'authenticationIndex']);
        Route::post('/settings/store', [SettingsController::class, 'store']);
        Route::post('/settings/taxes', [InvoiceTaxController::class, 'store']);
        Route::get('/settings/taxes/{id}/edit', [InvoiceTaxController::class, 'edit']);
        Route::post('/settings/taxes/{id}/update', [InvoiceTaxController::class, 'update']);
        Route::delete('/settings/taxes/{id}/delete', [InvoiceTaxController::class, 'destroy']);
    });

    Route::middleware(['permission:coupons-management'])->group(function () {
        Route::get('/coupons', [CouponsController::class, 'index']);
        Route::get('/coupons/create', [CouponsController::class, 'create']);
        Route::post('/coupons/store', [CouponsController::class, 'store']);
        Route::get('/coupons/{coupon}/edit', [CouponsController::class, 'edit'])->name('admin.coupons.edit');
        Route::put('/coupons/{coupon}', [CouponsController::class, 'update'])->name('admin.coupons.update');
        Route::post('/coupons/toggle-status', [CouponsController::class, 'toggleStatus'])->name('admin.coupons.toggleStatus');
        Route::delete('/coupons/{coupon}/delete', [CouponsController::class, 'destroy']);
        Route::get('/coupon-logs', [CouponsController::class, 'couponLogsIndex']);
        Route::get('/items-by-category', [CouponsController::class, 'getItemsByCategory']);
    });

    Route::middleware(['permission:payouts-management'])->group(function () {
        Route::get('/payouts', [PayoutsController::class, 'index']);
        Route::post('/payouts', [PayoutsController::class, 'store'])->name('admin.payout.submit');
        Route::post('/search/payouts', [PayoutsController::class, 'search'])->name('admin.payout.search');
        Route::post('/vendor/get-bank', [PayoutsController::class, 'getBank'])->name('vendor.getBank');
    });

    Route::middleware(['permission:reports-management'])->group(function () {
        Route::get('/reports', [AppController::class, 'report']);

        Route::get('/get-data-by-duration-12-month/{duration}', [AppController::class, 'getDataByDuration12Month']);
        Route::get('/vendor-earning-get-data-chart/{duration}', [AppController::class, 'vendorEarningGetDataChart']);
        Route::get('/get-payment-mode-by-duration/{duration}', [AppController::class, 'paymentModeGetChart']);
        Route::get('/get-item-category-sells-by-duration/{duration}', [AppController::class, 'itemCategorySellsGetChart']);
        Route::get('/get-data-for-earnings-profit/{duration}', [AppController::class, 'earningsProfitGetChart']);
        Route::get('/sales-data-items', [AppController::class, 'getSalesDataItemsChart']);
        Route::get('/get-top-ordered-item-data/{duration}', [AppController::class, 'topOrderedItemGetChart']);
        Route::get('/get-top-booked-play-area-data/{duration}', [AppController::class, 'topBookedPlayAreaChart']);
        Route::get('/get-all-data-chart', [AppController::class, 'getAllDataChart']);
        Route::get('/download-pdf/{duration}', [AppController::class, 'downloadPDF']);
        Route::get('/vendor-earnings-pdf/{duration}', [AppController::class, 'downloadVendorEarningsPDF']);
        Route::get('/payment-mode-pdf/{duration}', [AppController::class, 'generatePaymentModeReport']);

        Route::get('/generate-category-sales-report-pdf/{duration}', [AppController::class, 'generateCategorySalesReportPDF']);
        Route::get('/generate-earning-profit-report-pdf/{duration}', [AppController::class, 'generateEarningProfitReportPDF']);
        Route::get('/generate-profit-expense-report-pdf/{duration}', [AppController::class, 'generateProfitExpenseReportPDF']);
        Route::get('/generate-top-order-items-report-pdf/{duration}', [AppController::class, 'generateTopOrderItemReportPDF']);
        Route::get('/generate-top-play-area-booked-report-pdf/{duration}', [AppController::class, 'generateTopPlayAreaItemReportPDF']);
    });

    Route::middleware(['permission:dashboard-states'])->group(function () {
        Route::get('/admin-dashboard-stats', [AppController::class, 'adminDashboardStats'])->name('admin.dashboard.stats');
        Route::get('/get-data-by-duration/{duration}', [AppController::class, 'getDataByDuration']);
    });

    Route::middleware(['permission:dashboard-tickets-management'])->group(function () {
        Route::post('/tickets/store', [TicketController::class, 'store'])->name('admin.tickets.store');
        Route::post('/tickets/{ticket}/update', [TicketController::class, 'update']);
        Route::get('/tickets/{ticket}/update-status', [TicketController::class, 'updateStatus']);
    });

    Route::middleware(['permission:dashboard-latest'])->group(function () {
        Route::post('/trigger-birthday-reminder', [AppController::class, 'customerBirthdayReminder']);
    });

    Route::middleware(['permission:expenses-management'])->group(function () {
        Route::get('/expenses', [ExpenseController::class, 'index']);
        Route::post('/expenses/store', [ExpenseController::class, 'store']);
        Route::post('/expenses/{id}/update', [ExpenseController::class, 'update']);
        Route::delete('/expenses/{expense}/delete', [ExpenseController::class, 'destroy']);
        Route::get('/get-expense-data', [ExpenseController::class, 'getExpenseDrawer']);
        Route::put('/expenses/update-income', [ExpenseController::class, 'updateIncome']);
        Route::put('/expenses/update-budget', [ExpenseController::class, 'updateBudget']);

        Route::get('/expenses/export/csv', [ExpenseController::class, 'exportCSV'])->name('expenses.export.csv');
        Route::get('/expenses/export/excel', [ExpenseController::class, 'exportExcel'])->name('expenses.export.excel');
        Route::get('/expenses/export/pdf', [ExpenseController::class, 'exportPDF'])->name('expenses.export.pdf');
    });

    Route::middleware(['permission:play-areas-management'])->group(function () {
        Route::get('/play-areas', [PlayAreaController::class, 'index']);
        Route::post('/play-areas/store', [PlayAreaController::class, 'store']);
        Route::post('/update-play-area-status/{id}', [PlayAreaController::class, 'updateStatus']);
        Route::get('/play-areas/{id}/edit', [PlayAreaController::class, 'edit']);
        Route::post('/play-areas/{id}/update', [PlayAreaController::class, 'update']);
    });

    Route::middleware(['permission:events-management'])->group(function () {
        Route::get('/events', [EventController::class, 'index']);
        Route::post('/events/store', [EventController::class, 'store']);
        Route::post('/update-event-status/{id}', [EventController::class, 'updateStatus']);
        Route::post('/update-order-event-attendee-status/{id}', [EventController::class, 'updateOrderEventAttendeeStatus']);
        Route::get('/events/{id}/edit', [EventController::class, 'edit']);
        Route::get('/events/{id}/attendees', [EventController::class, 'attendees']);
        Route::post('/events/{id}/update', [EventController::class, 'update']);
    });

    Route::middleware(['permission:notifications-management'])->group(function () {
        Route::get('/notifications/email-templates/{id}', [EmailTemplateController::class, 'index']);
        Route::post('/notifications/email-templates/{id}', [EmailTemplateController::class, 'update']);
        Route::get('/notifications/promotions', [PromotionController::class, 'index']);
        Route::post('/notifications/promotions/send-push-notification', [PromotionController::class, 'sendPushNotification']);
        Route::post('/notifications/promotions/calculate-users', [PromotionController::class, 'calculateUsers']);
        Route::post('/notifications/promotions/store', [PromotionController::class, 'store']);
    });

    Route::middleware(['permission:billing-clients-management'])->group(function () {
        Route::get('/finance/billing-categories', [BillingCategoryController::class, 'index']);
        Route::post('/finance/billing-categories/store', [BillingCategoryController::class, 'store']);
        Route::get('/get-billing-category-data', [BillingCategoryController::class, 'show']);
        Route::post('/finance/billing-categories/{id}/update', [BillingCategoryController::class, 'update']);
        Route::delete('/finance/billing-categories/{billingCategory}/delete', [BillingCategoryController::class, 'destroy']);

        Route::get('/finance/clients', [ClientController::class, 'index']);
        Route::post('/finance/clients/store', [ClientController::class, 'store']);
        Route::get('/get-client-data', [ClientController::class, 'show']);
        Route::post('/finance/clients/{id}/update', [ClientController::class, 'update']);
        Route::delete('/finance/clients/{client}/delete', [ClientController::class, 'destroy']);

        Route::get('/finance/invoices', [InvoiceController::class, 'index']);
        Route::get('/finance/invoices/create', [InvoiceController::class, 'create']);
        Route::post('/finance/invoices/store', [InvoiceController::class, 'store']);
        Route::get('/finance/invoices/{invoice}', [InvoiceController::class, 'show']);
        Route::get('/finance/invoices/{invoice}/edit', [InvoiceController::class, 'edit']);
        Route::post('/finance/invoices/{id}/update', [InvoiceController::class, 'update']);
        Route::delete('/finance/invoices/{id}', [InvoiceController::class, 'destroy']);
        Route::post('/finance/invoices/preview', [InvoiceController::class, 'preview'])->name('invoice.preview');
        Route::post('/finance/invoices/download', [InvoiceController::class, 'downloadPdf']);
        Route::get('/finance/invoices/{invoice}/download', [InvoiceController::class, 'download']);
        Route::get('/finance/invoices/{invoice}/send', [InvoiceController::class, 'send']);
        Route::get('/finance/invoices/{invoice}/previewInvoice', [InvoiceController::class, 'previewInvoice']);
    });

    Route::middleware(['permission:user-management'])->group(function () {
        Route::get('/user-management/permissions', [PermissionController::class, 'index']);
        Route::post('/user-management/permissions', [PermissionController::class, 'store']);
        Route::delete('/user-management/permissions/{id}', [PermissionController::class, 'destroy']);

        Route::get('/user-management/roles', [RoleController::class, 'index']);
        Route::post('/user-management/roles', [RoleController::class, 'store']);
        Route::get('/user-management/roles/{role}', [RoleController::class, 'show']);
        Route::get('/user-management/roles/{role}/edit', [RoleController::class, 'edit']);
        Route::post('/user-management/roles/{role}/update', [RoleController::class, 'update']);
        Route::delete('/user-management/roles/{role}/delete', [RoleController::class, 'destroy']);

        Route::get('/user-management/users', [UserController::class, 'index']);
        Route::post('/user-management/users', [UserController::class, 'store']);
        Route::get('/user-management/users/{user}/edit', [UserController::class, 'edit']);
        Route::post('/user-management/users/{user}/update', [UserController::class, 'update']);
        Route::delete('/user-management/users/{user}/delete', [UserController::class, 'destroy']);
        Route::post('/register', [RegisteredUserController::class, 'storeAdmin']);
    });
});

Route::prefix('/vendor')->middleware(['auth', 'role:vendor'])->group(function () {
    Route::get('/dashboard', [VendorAppController::class, 'index']);
    Route::get('/vendor-dashboard-stats', [VendorAppController::class, 'vendorDashboardStats'])->name('vendor.dashboard.stats');
    Route::get('/get-data-by-duration/{duration}', [VendorAppController::class, 'getDataByDuration']);
    Route::get('/get-data-by-duration-12-month/{duration}', [VendorAppController::class, 'getDataByDuration12Month']);
    Route::get('/get-items-total-order-by-duration/{duration}', [VendorAppController::class, 'getDataItemTotalOrder']);

    Route::get('/reports', [VendorAppController::class, 'report']);


    Route::get('/items', [VendorItemsController::class, 'index']);
    Route::post('/items/store', [VendorItemsController::class, 'store']);
    Route::post('/update-item-status/{id}', [VendorItemsController::class, 'updateStatus']);
    Route::get('/items/{item}', [VendorItemsController::class, 'show']);
    Route::put('/items/{id}', [VendorItemsController::class, 'update']);
    Route::delete('/items/{id}/delete', [VendorItemsController::class, 'destroy']);
    Route::post('/search/items', [VendorItemsController::class, 'search'])->name('vendor.items.search');

    Route::get('/orders', [VendorOrdersController::class, 'index']);
    Route::get('/orders/{order}', [VendorOrdersController::class, 'show']);
    Route::delete('/orders/{id}/delete', [VendorOrdersController::class, 'destroy']);
    Route::put('/order-items/update-status', [VendorOrdersController::class, 'updateItemStatus'])->name('orderItems.update-status');
    Route::post('/orders/search', [VendorOrdersController::class, 'search'])->name('orders.search');

    Route::get('/payouts', [VendorPayoutsController::class, 'index']);
    Route::post('/payouts', [VendorPayoutsController::class, 'store'])->name('vendor.payout.submit');
    Route::post('/search/payouts', [VendorPayoutsController::class, 'search'])->name('vendor.payout.search');

    Route::get('/settings', [VendorAppController::class, 'settings']);
    Route::put('/settings', [VendorAppController::class, 'update'])->name('vendor.profile.update');

    Route::get('/get-order-data', [VendorOrdersController::class, 'getOrderDrawer']);

    Route::get('/coupons', [VendorCouponsController::class, 'index']);
    Route::get('/coupons/create', [VendorCouponsController::class, 'create']);
    Route::post('/coupons/store', [VendorCouponsController::class, 'store']);
    Route::get('/coupons/{coupon}/edit', [VendorCouponsController::class, 'edit'])->name('vendor.coupons.edit');
    Route::put('/coupons/{coupon}', [VendorCouponsController::class, 'update'])->name('vendor.coupons.update');
    Route::post('/coupons/toggle-status', [VendorCouponsController::class, 'toggleStatus'])->name('vendor.coupons.toggleStatus');
    Route::delete('/coupons/{coupon}/delete', [VendorCouponsController::class, 'destroy']);
    Route::get('/items-by-category', [VendorCouponsController::class, 'getItemsByCategory']);
});


Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/profile', [CustomerAppController::class, 'profile']);

    Route::post('rate/item', [CustomerAppController::class, 'rateItem'])->name('rate.item');
    Route::post('/submit-review', [CustomerAppController::class, 'reviewItem'])->name('submit.review');

    Route::get('/orders', [CustomerAppController::class, 'orders']);
    Route::get('/orders/{order}', [CustomerAppController::class, 'showOrder']);

    Route::post('/item/add-to-cart', [CartController::class, 'storeItemToCart']);
    Route::post('/play-area/add-to-cart', [CartController::class, 'storePlayAreaToCart']);
    Route::post('/event/add-to-cart', [CartController::class, 'storeEventToCart']);
    Route::post('/cart/remove-item', [CartController::class, 'removeFromCart']);
    Route::post('/update-play-area-cart', [CartController::class, 'updatePlayAreaDateTime']);
    Route::get('/clear-cart', [CartController::class, 'removeAllCart']);
    Route::post('/cart/update-event-quantity', [CartController::class, 'updateEventQuantity']);
    Route::post('/apply-coupon', [CartController::class, 'applyCoupon'])->name('apply-coupon');
    Route::post('/remove-coupon', [CartController::class, 'removeCoupon']);
    Route::post('/update-cart-point', [CartController::class, 'updateCartPoint']);

    Route::get('/checkout', [CheckoutController::class, 'index']);
    Route::post('/checkout/payment-callback', [CheckoutController::class, 'handlePaymentCallback']);
    Route::post('/checkout/payment-failed', [CheckoutController::class, 'handlePaymentFailed']);

    Route::get('/tickets', [CustomerAppController::class, 'tickets']);
    Route::post('/tickets/store', [CustomerTicketController::class, 'store'])->name('tickets.store');
    Route::post('/tickets/{ticket}/update', [CustomerTicketController::class, 'update']);
    Route::get('/tickets/{ticket}/update-status', [CustomerTicketController::class, 'updateStatus']);

    Route::get('/settings', [CustomerAppController::class, 'settings']);
    Route::put('/settings', [CustomerAppController::class, 'update'])->name('profile.update');
    Route::put('/settings-birthday', [CustomerAppController::class, 'updateBirthday'])->name('profile.update');

    Route::get('/get-order-data', [CustomerAppController::class, 'getOrderDrawer']);

    Route::get('/overview', [CustomerAppController::class, 'overview']);
});

Route::middleware('customRoleCheck:customer')->group(function () {
    Route::get('/', [CustomerAppController::class, 'foodItemIndex']);
    Route::get('/contact-us', [CustomerAppController::class, 'contactUsIndex']);
    Route::post('/contact-us/submit', [CustomerAppController::class, 'contactUsSendMail']);
});
Route::middleware('guest')->group(function () {
    Route::post('/item/book', [GuestAppController::class, 'addItemToSession'])->name('cart.add-to-session');
    Route::post('/cart/update-quantity', [GuestAppController::class, 'updateQuantity']);
    Route::post('/session/update-event-quantity', [GuestAppController::class, 'updateEventQuantity']);
    Route::post('/cart/session-remove-item', [GuestAppController::class, 'removeItem']);
    Route::get('/cart/session-clear', [GuestAppController::class, 'clearItems']);
    Route::post('/play-area/book', [GuestAppController::class, 'bookPlayArea']);
    Route::post('/event/book', [GuestAppController::class, 'bookEvent']);
    Route::post('/update-play-area-session', [GuestAppController::class, 'updatePlayAreaDateTime']);
});

Route::post('/store-subscription-id', [CustomerAppController::class, 'storeSubscriptionId'])->name('store.subscription.id');
Route::get('/search-items', [CartController::class, 'searchItems']);
Route::get('/search-products', [CartController::class, 'searchProducts']);
Route::get('/search-events', [CartController::class, 'searchEvents']);
Route::get('/search-play-area', [CartController::class, 'searchPlayArea']);
Route::get('/filter-by-vendor', [CartController::class, 'filterByVendor']);
Route::get('/get-items-data', [CustomerAppController::class, 'getItemsData']);

Route::get('/refresh-csrf', function () {
    return response()->json(['csrfToken' => csrf_token()]);
});
// Route::middleware(['auth'])->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';

<?php

namespace App\Http\Controllers\Admin;

use App\Events\BirthdayNotification;
use App\Http\Controllers\Controller;
use App\Models\AdminWallet;
use App\Models\AdminWalletLog;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Item;
use App\Models\Order;
use App\Models\Rating;
use App\Models\VendorBank;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\ItemCategory;
use App\Models\ItemRating;
use App\Models\Notification;
use App\Models\Payout;
use App\Models\VendorBankLog;
use App\Notifications\BirthdayReminder;
use Barryvdh\DomPDF\Facade\Pdf;
use Coderflex\LaravelTicket\Models\Ticket;
use Coderflex\LaravelTicket\Models\Category;
use Carbon\Carbon;
use DateInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;


        // Calculate Total datas
        $totalUsers         = User::role('customer')->get()->count();
        $totalVendors       = Vendor::whereNot('approve', 0)->count();
        $totalItems         = Item::whereNot('approve', 0)->whereNot('status', 0)->count();
        $totalItemsOrdered  = OrderItem::where('status', '!=', 'unpaid')->whereNotNull('item_id')->sum('quantity');
        $totalPlayAreasBooked  = OrderItem::where('status', '!=', 'unpaid')->whereNotNull('play_area_id')->count();
        $totalEventsBooked  = OrderItem::where('status', '!=', 'unpaid')->whereNotNull('event_id')->count();
        $totalRatings       = Rating::all()->count();
        $vendorEarnings     = VendorBank::all()->sum('total_earning');
        $adminEarnings      = AdminWallet::all()->sum('balance');
        $totalEarnings      = $vendorEarnings + $adminEarnings;

        $totalEarningsForItems = OrderItem::where('status', '!=', 'unpaid')->whereNotNull('item_id')->selectRaw('SUM(quantity * price) as total')->pluck('total')->first();
        // Step 1: Retrieve only necessary data
        $totalEarningsForPlayAreas = OrderItem::where('status', '!=', 'unpaid')->whereNotNull('play_area_id')->selectRaw('SUM(quantity * price) as total')->pluck('total')->first();
        $totalEarningsForEvents = OrderItem::where('status', '!=', 'unpaid')->whereNotNull('event_id')->selectRaw('SUM(quantity * price) as total')->pluck('total')->first();

        // Calculate Bookings data
        $ordersInProgress   = OrderItem::whereNot('item_id', null)->whereNot('status', 'delivered')->whereNot('status', 'rejected')->whereNot('status', 'unpaid')->get();
        $biggestOrders      = Order::orderBy('order_amount', 'desc')->whereNot('status', 'rejected')->whereNot('status', 'unpaid')->get()->take(10);
        $repeatCustomers    = Order::whereNot('status', 'unpaid')->groupBy('user_id')->select('user_id', DB::raw('count(*) as total'), DB::raw('SUM(order_amount) as total_amount'))->orderByDesc('total')->get()->take(10);
        $likedItems         = ItemRating::orderByDesc('rating')->get()->take(10);

        // Coupon Data
        $coupons = Coupon::withSum('orders as total_discount', 'coupon_discount')->withCount('orders')->orderByDesc('status')->get();

        // Tickets Data
        $categories = Category::where('is_visible', 1)->get();
        $tickets = Ticket::orderByRaw("CASE WHEN status = 'closed' THEN 1 ELSE 0 END") // Order closed tickets last
            ->orderBy('updated_at', 'desc') // Then order by updated_at descending
            ->get();

        $customers = User::role('customer')->get();

        $newCustomer = User::role('customer')->latest()->get()->take(10);

        // Fetch latest orders
        $latestOrders = Order::whereNot('status', 'unpaid')->latest()->take(10)->get();

        // Fetch latest payouts
        $latestPayouts = Payout::latest()->take(10)->get();

        // Fetch latest ratings
        $latestRatings = Rating::latest()->take(10)->get();

        $timelineItems = collect($latestOrders)->merge($latestPayouts)->merge($latestRatings)->sortByDesc('created_at')->values();

        $today = Carbon::now();
        $nextFiveDays = Carbon::now()->addDays(5);
        $prevFiveDays = Carbon::now()->subDays(5);

        $upcomingBirthdays = Customer::whereBetween('date_of_birth', [$today, $nextFiveDays])
            ->orderBy('date_of_birth')
            ->get();

        $pastBirthdays = Customer::whereBetween('date_of_birth', [$prevFiveDays, $today])
            ->orderBy('date_of_birth')
            ->get();

        return view('pages.dashboard.admin.dashboard', compact(['totalUsers', 'totalItemsOrdered', 'totalPlayAreasBooked', 'totalEventsBooked', 'totalItems', 'totalRatings', 'totalEarnings', 'vendorEarnings', 'totalVendors', 'ordersInProgress', 'biggestOrders', 'repeatCustomers', 'likedItems', 'coupons', 'categories', 'tickets', 'customers', 'newCustomer', 'timelineItems', 'upcomingBirthdays', 'pastBirthdays', 'totalEarningsForItems', 'totalEarningsForPlayAreas', 'totalEarningsForEvents']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function profileIndex()
    {
        return view('pages.dashboard.admin.profile');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore(Auth::id())],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Retrieve the authenticated user
        $user = User::find(auth()->id());
        // Update user details
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // Save the updated user record
        $user->save();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }


    public function getDataByDuration($duration)
    {
        $earnings = [];
        $orders = [];
        $reviews = [];
        $labels = [];
        $payouts = [];
        $customer = [];

        switch ($duration) {
            case 'today':
                $startDate = Carbon::now()->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                $interval = '3 hour'; // Hourly intervals
                $labelFormat = 'H:i';
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $interval = '1 day'; // Daily intervals
                $labelFormat = 'D'; // Day of the week (e.g., Mon, Tue, ...)
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $interval = '2 day'; // Daily intervals
                $labelFormat = 'jS'; // Day of the month (e.g., 1st, 2nd, ...)
                break;
            case 'lifetime':
                $startDate = Carbon::now()->subMonths(7)->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $interval = '1 month'; // Monthly intervals for the last 8 months
                $labelFormat = 'M Y'; // Month and year (e.g., Jan 2023, Feb 2023, ...)
                break;
            default:
                return response()->json(['error' => 'Invalid duration specified.'], 400);
        }

        // Generate labels and initialize interval
        $currentDate = $startDate;
        while ($currentDate <= $endDate) {
            $labels[] = $currentDate->format($labelFormat);
            $nextDate = $currentDate->copy()->add($interval);
            $earnings[] = $this->calculateEarnings($currentDate, $nextDate);
            $orders[] = $this->calculateOrders($currentDate, $nextDate);
            $reviews[] = $this->calculateReviews($currentDate, $nextDate);
            $payouts[] = $this->calculatePayouts($currentDate, $nextDate);
            $customer[] = $this->calculateCustomers($currentDate, $nextDate);
            $currentDate = $nextDate;
        }

        // Prepare and return response
        $response = [
            'labels' => $labels,
            'orders' => $orders,
            'reviews' => $reviews,
            'customers' => $customer,
        ];
    
        if (auth()->user()->can('dashboard-pricing-information')) {
            $response['earnings'] = $earnings;
            $response['payouts'] = $payouts;
        }
    

        return response()->json($response);
    }

    private function calculateEarnings($startDate, $endDate)
    {
        $logs = AdminWalletLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('action', 'deposit')
            ->get();

        $adminEarnings = $logs->sum(function ($log) {
            return $log->amount;
        });

        $logs = AdminWalletLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('action', 'withdraw')
            ->get();

        $adminCost = $logs->sum(function ($log) {
            return $log->amount;
        });

        return (int) $adminEarnings - $adminCost;
    }

    private function calculateOrders($startDate, $endDate)
    {
        $orderItems = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'unpaid')
            ->get();

        $adminEarnings = $orderItems->count();

        return (int) $adminEarnings;
    }

    private function calculateCustomers($startDate, $endDate)
    {
        $orderItems = User::role('customer')->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $adminEarnings = $orderItems->count();

        return (int) $adminEarnings;
    }

    private function calculateReviews($startDate, $endDate)
    {
        $orderItems = Rating::whereBetween('created_at', [$startDate, $endDate])
            ->get();
        $adminEarnings = $orderItems->count();

        return (int) $adminEarnings;
    }

    private function calculateRevenues($startDate, $endDate)
    {
        $orderItems = OrderItem::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'unpaid')->where('refunded', '!=', 1)
            ->get();

        $totalEarnings = $orderItems->sum(function ($orderItem) {
            return $orderItem->price * $orderItem->quantity;
        });

        return (int) $totalEarnings;
    }


    private function calculatePayouts($startDate, $endDate)
    {
        $payouts = Payout::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'rejected')
            ->get();

        $totalPayouts = $payouts->sum(function ($payout) {
            return $payout->request_amount;
        });

        return (int) $totalPayouts;
    }

    private function calculateNetEarnings($startDate, $endDate)
    {
        $logs = AdminWalletLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('action', 'deposit')
            ->get();

        $adminEarnings = $logs->sum(function ($log) {
            return $log->amount;
        });

        $logs = AdminWalletLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('action', 'withdraw')
            ->get();

        $adminCost = $logs->sum(function ($log) {
            return $log->amount;
        });

        $admin = $adminEarnings - $adminCost;


        $logs = VendorBankLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('action', 'deposit')
            ->get();

        $vendorEarnings = $logs->sum(function ($log) {
            return $log->amount;
        });

        $logs = VendorBankLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('action', 'withdraw')
            ->get();

        $vendorCost = $logs->sum(function ($log) {
            return $log->amount;
        });

        $vendor = $vendorEarnings - $vendorCost;

        return (int) ($admin + $vendor);
    }

    private function calculateExpenses($startDate, $endDate)
    {
        $expenses = Expense::whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $totalExpenses = $expenses->sum(function ($expense) {
            return $expense->amount;
        });

        return (int) $totalExpenses;
    }

    public function getDataByDuration12Month($duration)
    {
        $earnings = [];
        $revenues = [];
        $labels = [];
        $profit = [];
        $expenses = [];

        switch ($duration) {
            case 'today':
                $startDate = Carbon::now()->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                $interval = '2 hour'; // Hourly intervals
                $labelFormat = 'H:i';
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $interval = '1 day'; // Daily intervals
                $labelFormat = 'D'; // Day of the week (e.g., Mon, Tue, ...)
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $interval = '2 day'; // Daily intervals
                $labelFormat = 'jS'; // Day of the month (e.g., 1st, 2nd, ...)
                break;
            case 'lifetime':
                $startDate = Carbon::now()->subMonths(11)->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $interval = '1 month'; // Monthly intervals for the last 8 months
                $labelFormat = 'M'; // Month and year (e.g., Jan 2023, Feb 2023, ...)
                break;
            default:
                return response()->json(['error' => 'Invalid duration specified.'], 400);
        }

        // Generate labels and initialize interval
        $currentDate = $startDate;
        while ($currentDate <= $endDate) {
            $labels[] = $currentDate->format($labelFormat);
            $nextDate = $currentDate->copy()->add($interval);
            $earnings[] = $this->calculateNetEarnings($currentDate, $nextDate);
            $revenues[] = $this->calculateRevenues($currentDate, $nextDate);
            $expenses[] = $this->calculateExpenses($currentDate, $nextDate);
            $profit[] = $this->calculateAdminEarnings($currentDate, $nextDate);
            $currentDate = $nextDate;
        }

        // Prepare and return response
        $response = [
            'labels' => $labels,
            'earnings' => $earnings,
            'revenues' => $revenues,
            'expenses' => $expenses,
            'profit' => $profit
        ];

        return response()->json($response);
    }
    /**
     * Display the specified resource.
     */
    public function report()
    {
        // Define the statuses you want to count
        $statuses = ['delivered', 'rejected', 'accepted', 'completed'];

        // Initialize an array to hold the counts for each status
        $statusCounts = [];

        // Loop through each status and count the corresponding OrderItems
        foreach ($statuses as $status) {
            $count = OrderItem::where('status', $status)
                ->count();

            // Store the count in the statusCounts array with the status as the key
            $statusCounts[] = $count;
        }

        $months = collect(range(0, 11))->map(function ($i) {
            return Carbon::now()->subMonths($i)->format('Y-m');
        })->reverse();

        // Fetch categories with items and their sales
        $categories = ItemCategory::with(['items' => function ($query) use ($months) {
            $query->with(['orderItems' => function ($query) use ($months) {
                $query->whereIn(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), $months);
            }]);
        }])->get();

        $salesData = [];

        foreach ($categories as $category) {
            $categoryData = [];

            foreach ($category->items as $item) {
                $itemData = ['item' => $item->item_name];

                foreach ($months as $month) {
                    $monthlySales = $item->orderItems->filter(function ($orderItem) use ($month) {
                        return $orderItem->created_at->format('Y-m') == $month;
                    })->sum('quantity');

                    $itemData[$month] = $monthlySales;
                }

                $categoryData[] = $itemData;
            }

            $salesData[$category->name] = $categoryData;
        }

        $topItems = OrderItem::select('item_id', DB::raw('SUM(quantity) as total_quantity'))
            ->where('item_id', '!=', null)
            ->where('status', '!=', 'unpaid')
            ->groupBy('item_id')
            ->orderByDesc('total_quantity')
            ->limit(6)
            ->get();

        // Prepare the data for the chart
        $seriesData = $topItems->map(function ($item) {
            return [
                'name' => $item->item->item_name,
                'count' => $item->total_quantity,
            ];
        });



        return view('pages.report.admin.report', compact('statusCounts', 'salesData', 'months', 'seriesData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function customerBirthdayReminder(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'customer_id' => 'required|exists:users,id', // Ensure customer_id exists in users table
        ]);

        Notification::create([
            'user_id' => $request->customer_id,
            'message' => 'Happy Birthday! Wishing you a fantastic day filled with joy and happiness.',
        ]);

        return response()->json(['message' => 'Birthday wishes have been sent out!']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function vendorEarningGetDataChart($duration)
    {
        $earnings = [];
        $labels = [];
        $startDate = null;
        $endDate = null;

        switch ($duration) {
            case 'today':
                $startDate = Carbon::now()->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                $interval = '2 hour'; // Hourly intervals
                $labelFormat = 'H:i';
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $interval = '1 day'; // Daily intervals
                $labelFormat = 'D'; // Day of the week (e.g., Mon, Tue, ...)
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $interval = '2 day'; // Daily intervals
                $labelFormat = 'jS'; // Day of the month (e.g., 1st, 2nd, ...)
                break;
            case 'lifetime':
                $startDate = Carbon::now()->subMonths(11)->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $interval = '1 month'; // Monthly intervals for the last 8 months
                $labelFormat = 'M'; // Month and year (e.g., Jan 2023, Feb 2023, ...)
                break;
            default:
                return response()->json(['error' => 'Invalid duration specified.'], 400);
        }

        // Generate labels and initialize interval
        $currentDate = $startDate;
        $vendors = User::role('vendor')->get();

        foreach ($vendors as $vendor) {
            $labels[] = $vendor->vendor->brand_name;

            $currentDate = $startDate;
            $vendorEarnings = 0;

            while ($currentDate <= $endDate) {
                $nextDate = $currentDate->copy()->add($interval);
                $vendorEarnings += $this->calculateIndividualVendorEarnings($vendor->vendor->id, $currentDate, $nextDate);
                $currentDate = $nextDate;
            }

            // Add vendor's earnings to the earnings array
            $earnings[] = $vendorEarnings;
        }

        // Prepare and return response
        $response = [
            'labels' => $labels,
            'earnings' => $earnings,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];

        return response()->json($response);
    }

    private function calculateIndividualVendorEarnings($vendorId, $startDate, $endDate)
    {
        // Calculate and return earnings for the specified date range and vendor ID
        $logs = VendorBankLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('action', 'deposit')->where('vendor_id', $vendorId)
            ->get();

        $adminEarnings = $logs->sum(function ($log) {
            return $log->amount;
        });

        $logs = VendorBankLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('action', 'withdraw')->where('vendor_id', $vendorId)
            ->get();

        $adminCost = $logs->sum(function ($log) {
            return $log->amount;
        });

        return (int) $adminEarnings - $adminCost;
    }

    public function paymentModeGetChart($duration)
    {
        $labels = [];
        $series = [];
        $seriesData = [];
        switch ($duration) {
            case 'today':
                $startDate = Carbon::now()->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                $interval = '2 hour'; // Hourly intervals
                $labelFormat = 'H:i';
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $interval = '1 day'; // Daily intervals
                $labelFormat = 'D'; // Day of the week (e.g., Mon, Tue, ...)
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $interval = '2 day'; // Daily intervals
                $labelFormat = 'jS'; // Day of the month (e.g., 1st, 2nd, ...)
                break;
            case 'lifetime':
                $startDate = Carbon::now()->subMonths(11)->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $interval = '1 month'; // Monthly intervals for the last 8 months
                $labelFormat = 'M'; // Month and year (e.g., Jan 2023, Feb 2023, ...)
                break;
            default:
                return response()->json(['error' => 'Invalid duration specified.'], 400);
        }

        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $labels[] = $currentDate->format($labelFormat);
            $currentDate->add($interval);
        }

        // Initialize series data with zeros
        $allPaymentMethods = DB::table('orders')
            ->where('status', '!=', 'unpaid')
            ->select('payment_method')
            ->distinct()
            ->pluck('payment_method')
            ->toArray();

        foreach ($allPaymentMethods as $method) {
            $seriesData[$method] = array_fill(0, count($labels), 0);
        }


        // Fill series data with actual counts
        $currentDate = $startDate;
        $labelIndex = 0;

        while ($currentDate <= $endDate) {
            $nextDate = $currentDate->copy()->add($interval);
            $paymentCounts = $this->calculatePaymentModeUsed($currentDate, $nextDate);
            foreach ($paymentCounts as $count) {
                $seriesData[$count['name']][$labelIndex] = $count['data'];
            }
            $currentDate = $nextDate;
            $labelIndex++;
        }

        // Convert series data to correct format
        foreach ($seriesData as $method => $data) {
            $series[] = [
                'name' => $method,
                'data' => $data
            ];
        }

        // Prepare and return response
        $response = [
            'labels' => $labels,
            'series' => $series,
        ];

        return response()->json($response);
    }

    private function calculatePaymentModeUsed($startDate, $endDate)
    {
        $paymentMethods = DB::table('orders')
            ->where('status', '!=', 'unpaid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('payment_method', DB::raw('count(*) as count'))
            ->groupBy('payment_method')
            ->get();

        // Prepare data for the chart
        $data = $paymentMethods->map(function ($item) {
            return [
                'name' => $item->payment_method,
                'data' => $item->count
            ];
        });

        return $data;
    }


    public function itemCategorySellsGetChart($duration)
    {
        $labels = [];
        $series = [];
        $seriesData = [];

        switch ($duration) {
            case 'today':
                $startDate = Carbon::now()->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                $interval = new DateInterval('PT2H'); // Hourly intervals
                $labelFormat = 'H:i';
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $interval = new DateInterval('P1D'); // Daily intervals
                $labelFormat = 'D'; // Day of the week (e.g., Mon, Tue, ...)
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $interval = new DateInterval('P2D'); // Daily intervals
                $labelFormat = 'jS'; // Day of the month (e.g., 1st, 2nd, ...)
                break;
            case 'lifetime':
                $startDate = Carbon::now()->subMonths(11)->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $interval = new DateInterval('P1M'); // Monthly intervals for the last 8 months
                $labelFormat = 'M'; // Month and year (e.g., Jan 2023, Feb 2023, ...)
                break;
            default:
                return response()->json(['error' => 'Invalid duration specified.'], 400);
        }

        // Initialize labels
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $labels[] = $currentDate->format($labelFormat);
            $currentDate->add($interval);
        }

        // Initialize series data with zeros
        $allCategories = DB::table('item_categories')
            ->select('name')
            ->distinct()
            ->pluck('name')
            ->toArray();

        foreach ($allCategories as $category) {
            $seriesData[$category] = array_fill(0, count($labels), 0);
        }

        // Fill series data with actual counts
        $currentDate = $startDate->copy();
        $labelIndex = 0;
        while ($currentDate <= $endDate) {
            $nextDate = $currentDate->copy()->add($interval);
            $categorySales = $this->calculateCategorySales($currentDate, $nextDate);
            foreach ($categorySales as $sale) {
                $seriesData[$sale['name']][$labelIndex] = $sale['data'];
            }
            $currentDate = $nextDate;
            $labelIndex++;
        }

        // Convert series data to correct format
        foreach ($seriesData as $category => $data) {
            $series[] = [
                'name' => $category,
                'data' => $data
            ];
        }

        // Prepare and return response
        $response = [
            'labels' => $labels,
            'series' => $series,
        ];

        return response()->json($response);
    }

    private function calculateCategorySales($startDate, $endDate)
    {
        $categorySales = DB::table('order_items')
            ->join('items', 'order_items.item_id', '=', 'items.id')
            ->join('item_categories', 'items.category_id', '=', 'item_categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'unpaid')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select('item_categories.name as category_name', DB::raw('SUM(order_items.price * order_items.quantity) as total_sales'))
            ->groupBy('item_categories.name')
            ->get();

        $data = $categorySales->map(function ($item) {
            return [
                'name' => $item->category_name,
                'data' => $item->total_sales
            ];
        });

        return $data;
    }

    public function earningsProfitGetChart($duration)
    {
        $labels = [];
        $earnings = [];
        $profit = [];
        $vendorEarnings = [];

        switch ($duration) {
            case 'today':
                $startDate = Carbon::now()->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                $interval = '2 hour'; // Hourly intervals
                $labelFormat = 'H:i';
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $interval = '1 day'; // Daily intervals
                $labelFormat = 'D'; // Day of the week (e.g., Mon, Tue, ...)
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $interval = '2 day'; // Daily intervals
                $labelFormat = 'jS'; // Day of the month (e.g., 1st, 2nd, ...)
                break;
            case 'lifetime':
                $startDate = Carbon::now()->subMonths(11)->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $interval = '1 month'; // Monthly intervals for the last 8 months
                $labelFormat = 'M'; // Month and year (e.g., Jan 2023, Feb 2023, ...)
                break;
            default:
                return response()->json(['error' => 'Invalid duration specified.'], 400);
        }

        // Generate labels and initialize interval
        $currentDate = $startDate;
        while ($currentDate <= $endDate) {
            $labels[] = $currentDate->format($labelFormat);
            $nextDate = $currentDate->copy()->add($interval);
            $earnings[] = $this->calculateRevenues($currentDate, $nextDate);
            $profit[] = $this->calculateAdminEarnings($currentDate, $nextDate);
            $vendorEarnings[] = $this->calculateVendorEarnings($currentDate, $nextDate);
            $currentDate = $nextDate;
        }

        // Prepare and return response
        $response = [
            'labels' => $labels,
            'earnings' => $earnings,
            'profit' => $profit,
            'vendorEarnings' => $vendorEarnings
        ];

        return response()->json($response);
    }

    private function calculateVendorEarnings($startDate, $endDate)
    {
        // Calculate and return earnings for the specified date range and vendor ID
        $logs = VendorBankLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('action', 'deposit')->get();

        $adminEarnings = $logs->sum(function ($log) {
            return $log->amount;
        });

        $logs = VendorBankLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('action', 'withdraw')
            ->get();

        $adminCost = $logs->sum(function ($log) {
            return $log->amount;
        });

        return (int) $adminEarnings - $adminCost;
    }
    private function calculateAdminEarnings($startDate, $endDate)
    {
        // Calculate and return earnings for the specified date range and vendor ID
        $logs = AdminWalletLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('action', 'deposit')->get();

        $adminEarnings = $logs->sum(function ($log) {
            return $log->amount;
        });

        $logs = AdminWalletLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('action', 'withdraw')
            ->get();

        $adminCost = $logs->sum(function ($log) {
            return $log->amount;
        });

        return (int) $adminEarnings - $adminCost;
    }

    public function topOrderedItemGetChart($duration)
    {

        switch ($duration) {
            case 'today':
                $startDate = Carbon::now()->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                $interval = '2 hour'; // Hourly intervals
                $labelFormat = 'H:i';
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $interval = '1 day'; // Daily intervals
                $labelFormat = 'D'; // Day of the week (e.g., Mon, Tue, ...)
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $interval = '2 day'; // Daily intervals
                $labelFormat = 'jS'; // Day of the month (e.g., 1st, 2nd, ...)
                break;
            case 'lifetime':
                $startDate = Carbon::now()->subMonths(11)->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $interval = '1 month'; // Monthly intervals for the last 8 months
                $labelFormat = 'M'; // Month and year (e.g., Jan 2023, Feb 2023, ...)
                break;
            default:
                return response()->json(['error' => 'Invalid duration specified.'], 400);
        }

        $topItems = OrderItem::select('item_id', DB::raw('SUM(quantity) as total_quantity'))
            ->where('item_id', '!=', null)
            ->where('status', '!=', 'unpaid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('item_id')
            ->orderByDesc('total_quantity')
            ->limit(6)
            ->get();

        // Prepare the data for the chart
        $seriesData = $topItems->map(function ($item) {
            return [
                'name' => $item->item->item_name,
                'count' => $item->total_quantity,
            ];
        });

        return response()->json(['data' => $seriesData]);
    }

    public function topBookedPlayAreaChart($duration)
    {

        switch ($duration) {
            case 'today':
                $startDate = Carbon::now()->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                $interval = '2 hour'; // Hourly intervals
                $labelFormat = 'H:i';
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $interval = '1 day'; // Daily intervals
                $labelFormat = 'D'; // Day of the week (e.g., Mon, Tue, ...)
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $interval = '2 day'; // Daily intervals
                $labelFormat = 'jS'; // Day of the month (e.g., 1st, 2nd, ...)
                break;
            case 'lifetime':
                $startDate = Carbon::now()->subMonths(11)->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $interval = '1 month'; // Monthly intervals for the last 8 months
                $labelFormat = 'M'; // Month and year (e.g., Jan 2023, Feb 2023, ...)
                break;
            default:
                return response()->json(['error' => 'Invalid duration specified.'], 400);
        }

        $topPlayAreas = OrderItem::select('play_area_id', DB::raw('SUM(quantity) as total_quantity'))
            ->where('play_area_id', '!=', null)
            ->where('status', '!=', 'unpaid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('play_area_id')
            ->orderByDesc('total_quantity')
            ->limit(6)
            ->get();

        // Prepare the data for the chart
        $seriesData = $topPlayAreas->map(function ($playArea) {
            return [
                'name' => $playArea->playArea->title,
                'count' => $playArea->total_quantity,
            ];
        });

        return response()->json(['data' => $seriesData]);
    }

    public function getAllDataChart(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        $earnings = [];
        $revenues = [];
        $labels = [];
        $profit = [];
        $expenses = [];

        $paymentModeLabels = [];
        $paymentModeSeries = [];
        $paymentModeSeriesData = [];

        $categorySellsLabels = [];
        $categorySellsSeries = [];
        $categorySellsSeriesData = [];

        $allVendorEarnings = [];

        if ($startDate->isSameDay($endDate)) {
            $interval = '2 hour';
            $labelFormat = 'H:i';
        } elseif ($startDate->isSameWeek($endDate)) {
            $interval = '1 day';
            $labelFormat = 'D';
        } elseif ($startDate->isSameMonth($endDate)) {
            $interval = '2 day';
            $labelFormat = 'jS';
        } else {
            $interval = '1 day';
            $labelFormat = 'j';
        }


        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $categorySellsLabels[] = $currentDate->format($labelFormat);
            $currentDate->add($interval);
        }

        // Initialize series data with zeros
        $allCategories = DB::table('item_categories')
            ->select('name')
            ->distinct()
            ->pluck('name')
            ->toArray();

        foreach ($allCategories as $category) {
            $categorySellsSeriesData[$category] = array_fill(0, count($categorySellsLabels), 0);
        }

        // Fill series data with actual counts
        $currentDate = $startDate->copy();
        $labelIndex = 0;
        while ($currentDate <= $endDate) {
            $nextDate = $currentDate->copy()->add($interval);
            $categorySales = $this->calculateCategorySales($currentDate, $nextDate);
            foreach ($categorySales as $sale) {
                $categorySellsSeriesData[$sale['name']][$labelIndex] = $sale['data'];
            }
            $currentDate = $nextDate;
            $labelIndex++;
        }

        // Convert series data to correct format
        foreach ($categorySellsSeriesData as $category => $data) {
            $categorySellsSeries[] = [
                'name' => $category,
                'data' => $data
            ];
        }

        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $paymentModeLabels[] = $currentDate->format($labelFormat);
            $currentDate->add($interval);
        }

        // Initialize series data with zeros
        $allPaymentMethods = DB::table('orders')
            ->where('status', '!=', 'unpaid')
            ->select('payment_method')
            ->distinct()
            ->pluck('payment_method')
            ->toArray();

        foreach ($allPaymentMethods as $method) {
            $paymentModeSeriesData[$method] = array_fill(0, count($paymentModeLabels), 0);
        }


        // Fill series data with actual counts
        $currentDate = $startDate;
        $labelIndex = 0;

        while ($currentDate <= $endDate) {
            $nextDate = $currentDate->copy()->add($interval);
            $paymentCounts = $this->calculatePaymentModeUsed($currentDate, $nextDate);
            foreach ($paymentCounts as $count) {
                $paymentModeSeriesData[$count['name']][$labelIndex] = $count['data'];
            }
            $currentDate = $nextDate;
            $labelIndex++;
        }

        // Convert series data to correct format
        foreach ($paymentModeSeriesData as $method => $data) {
            $paymentModeSeries[] = [
                'name' => $method,
                'data' => $data
            ];
        }


        $currentDate = $startDate;
        while ($currentDate <= $endDate) {
            // Format the current date based on the label format
            $labels[] = $currentDate->format($labelFormat);
            $nextDate = $currentDate->copy()->add($interval);
            $earnings[] = $this->calculateNetEarnings($currentDate, $nextDate);
            $revenues[] = $this->calculateRevenues($currentDate, $nextDate);
            $expenses[] = $this->calculateExpenses($currentDate, $nextDate);
            $profit[] = $this->calculateAdminEarnings($currentDate, $nextDate);
            $allVendorEarnings[] = $this->calculateVendorEarnings($currentDate, $nextDate);
            $currentDate = $nextDate;
        }

        $VendorEarningsArray = [];
        $vendorNamesAsLabel = [];

        $vendors = User::role('vendor')->get();

        foreach ($vendors as $vendor) {
            $vendorNamesAsLabel[] = $vendor->vendor->brand_name;

            $vendorEarnings = 0;
            $currentDate = $startDate->copy();

            while ($currentDate <= $endDate) {
                $nextDate = $currentDate->copy()->addDay(); // Adjust interval as needed
                $vendorEarnings += $this->calculateIndividualVendorEarnings($vendor->vendor->id, $currentDate, $nextDate);
                $currentDate = $nextDate;
            }

            // Add vendor's earnings to the earnings array
            $VendorEarningsArray[] = $vendorEarnings;
        }

        $topItems = OrderItem::select('item_id', DB::raw('SUM(quantity) as total_quantity'))
            ->where('status', '!=', 'unpaid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('item_id')
            ->orderByDesc('total_quantity')
            ->limit(6)
            ->get();

        // Prepare the data for the chart
        $topOrderedItemsData = $topItems->map(function ($item) {
            return [
                'name' => $item->item->item_name,
                'count' => $item->total_quantity,
            ];
        });
        // Prepare and return response
        $response = [
            'vendorNamesAsLabel' => $vendorNamesAsLabel,
            'VendorEarningsArray' => $VendorEarningsArray,
            'vendorEarnings' => $allVendorEarnings,
            'labels' => $labels,
            'earnings' => $earnings,
            'revenues' => $revenues,
            'expenses' => $expenses,
            'profit' => $profit,
            'paymentModeLabels' => $paymentModeLabels,
            'paymentModeSeries' => $paymentModeSeries,
            'categorySellsLabels' => $categorySellsLabels,
            'categorySellsSeries' => $categorySellsSeries,
            'topOrderedItemsData' => $topOrderedItemsData
        ];

        return response()->json($response);
    }

    public function downloadPDF($duration)
    {
        $data = $this->getDataByDuration12Month($duration)->getData();

        $pdf = Pdf::loadView('pdf.pdf-template', ['data' => $data, 'duration' => $duration]);

        return $pdf->download('report.pdf');
    }

    public function downloadVendorEarningsPDF($duration)
    {
        $data = $this->vendorEarningGetDataChart($duration)->getData();
        $pdf = Pdf::loadView('pdf.pdf-vendor-earnings-template', ['data' => $data, 'duration' => $duration]);
        return $pdf->download('vendor_earnings_report.pdf');
    }

    public function generatePaymentModeReport($duration)
    {
        // Fetch chart data
        $chartData = $this->paymentModeGetChart($duration)->getData();

        // Load the PDF view and pass the chart data
        $pdf = PDF::loadView('pdf.payment_mode_report', ['data' => $chartData]);

        // Return the PDF response
        return $pdf->download('payment_mode_report.pdf');
    }

    public function generateCategorySalesReportPDF($duration)
    {
        $chartData = $this->itemCategorySellsGetChart($duration)->getData();
        $chartData = json_decode(json_encode($chartData)); // Convert array to object

        $data = (object) [
            'labels' => $chartData->labels,
            'series' => $chartData->series
        ];

        $pdf = PDF::loadView('pdf.category_sales', compact('data', 'duration'));
        return $pdf->download('category_sales_report.pdf');
    }

    public function generateEarningProfitReportPDF($duration)
    {
        $data = $this->earningsProfitGetChart($duration)->getData();

        $pdf = Pdf::loadView('pdf.earnings-profit-pdf-template', ['data' => $data, 'duration' => $duration]);

        return $pdf->download('earning_profit.pdf');
    }

    public function generateProfitExpenseReportPDF($duration)
    {
        $data = $this->getDataByDuration12Month($duration)->getData();

        $pdf = Pdf::loadView('pdf.profit-expense-template', ['data' => $data, 'duration' => $duration]);

        return $pdf->download('profit_expense.pdf');
    }

    public function generateTopOrderItemReportPDF($duration)
    {
        $data = $this->topOrderedItemGetChart($duration)->getData();

        $pdf = Pdf::loadView('pdf.top-order-item', ['data' => $data, 'duration' => $duration]);

        return $pdf->download('Top-Ordered-Item.pdf');
    }

    public function generateTopPlayAreaItemReportPDF($duration)
    {
        $data = $this->topBookedPlayAreaChart($duration)->getData();

        $pdf = Pdf::loadView('pdf.top-booked-play-area', ['data' => $data, 'duration' => $duration]);

        return $pdf->download('Top-Booked-Play-Area.pdf');
    }

    public function adminDashboardStats()
    {
        $totalItemsOrdered  = OrderItem::where('status', '!=', 'unpaid')->whereNotNull('item_id')->sum('quantity');
        $totalPlayAreasBooked  = OrderItem::where('status', '!=', 'unpaid')->whereNotNull('play_area_id')->count();
        $totalEventsBooked  = OrderItem::where('status', '!=', 'unpaid')->whereNotNull('event_id')->count();

        $totalEarningsForItems = OrderItem::where('status', '!=', 'unpaid')->whereNotNull('item_id')->selectRaw('SUM(quantity * price) as total')->pluck('total')->first();
        // Step 1: Retrieve only necessary data
        $totalEarningsForPlayAreas = OrderItem::where('status', '!=', 'unpaid')->whereNotNull('play_area_id')->selectRaw('SUM(quantity * price) as total')->pluck('total')->first();
        $totalEarningsForEvents = OrderItem::where('status', '!=', 'unpaid')->whereNotNull('event_id')->selectRaw('SUM(quantity * price) as total')->pluck('total')->first();

        $vendorEarnings     = VendorBank::all()->sum('total_earning');

        return response()->json([
            'totalItemsOrdered' => number_format($totalItemsOrdered),
            'totalPlayAreasBooked' => number_format($totalPlayAreasBooked),
            'totalEventsBooked' => number_format($totalEventsBooked),
            'totalEarningsForItems' => number_format($totalEarningsForItems),
            'totalEarningsForPlayAreas' => number_format($totalEarningsForPlayAreas),
            'totalEarningsForEvents' => number_format($totalEarningsForEvents),
            'vendorEarnings' => number_format($vendorEarnings),
        ]);
    }
}

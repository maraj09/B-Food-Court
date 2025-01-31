<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\ItemRating;
use App\Models\OrderItem;
use App\Models\Payout;
use App\Models\Rating;
use App\Models\VendorBank;
use App\Models\VendorBankLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class AppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendorId = Auth::user()->vendor->id;
        $orderItems = OrderItem::whereHas('item', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })->where('status', '!=', 'unpaid')->get();
        $topItems = OrderItem::whereHas('item', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })
            ->where('status', '!=', 'unpaid')
            ->select('item_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(quantity * price) as total_earnings'))
            ->groupBy('item_id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        $topOrderItems = OrderItem::whereHas('item', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })->where('status', '!=', 'unpaid')->orderByDesc('created_at')->get();

        $latestRatings = Rating::whereHas('item', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })->orderByDesc('created_at')->take(10)->get();

        return view('pages.dashboard.vendor.dashboard', compact(['orderItems', 'topItems', 'topOrderItems', 'latestRatings']));
    }

    public function vendorDashboardStats()
    {
        $vendorId = auth()->user()->vendor->id;
        $totalOrders = OrderItem::whereHas('item', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })
            ->where('status', '!=', 'unpaid')
            ->count();
        $totalEarning = VendorBank::where('vendor_id', $vendorId)->first()->total_earning;

        return response()->json([
            'totalOrders' => number_format($totalOrders),
            'totalEarning' => number_format($totalEarning),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getDataByDuration12Month($duration)
    {
        $earnings = [];
        $revenues = [];
        $labels = [];
        $payouts = [];

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
            $earnings[] = $this->calculateEarnings($currentDate, $nextDate);
            $revenues[] = $this->calculateRevenues($currentDate, $nextDate);
            $payouts[] = $this->calculatePayouts($currentDate, $nextDate);
            $currentDate = $nextDate;
        }

        // Prepare and return response
        $response = [
            'labels' => $labels,
            'earnings' => $earnings,
            'revenues' => $revenues,
            'payouts' => $payouts
        ];

        return response()->json($response);
    }

    public function getDataByDuration($duration)
    {
        $earnings = [];
        $orders = [];
        $reviews = [];
        $labels = [];
        $payouts = [];

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
            $currentDate = $nextDate;
        }

        // Prepare and return response
        $response = [
            'labels' => $labels,
            'earnings' => $earnings,
            'orders' => $orders,
            'reviews' => $reviews,
            'payouts' => $payouts
        ];

        return response()->json($response);
    }

    private function calculateEarnings($startDate, $endDate)
    {
        // Calculate and return earnings for the specified date range (e.g., hourly, daily, monthly)
        $vendorId = Auth::user()->vendor->id;
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

    private function calculateOrders($startDate, $endDate)
    {
        // Calculate and return earnings for the specified date range (e.g., hourly, daily, monthly)
        $vendorId = Auth::user()->vendor->id;
        $orderItems = OrderItem::whereHas('item', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'unpaid')
            ->get();
        $adminEarnings = $orderItems->count();

        return (int) $adminEarnings;
    }

    private function calculateReviews($startDate, $endDate)
    {
        // Calculate and return earnings for the specified date range (e.g., hourly, daily, monthly)
        $vendorId = Auth::user()->vendor->id;
        $orderItems = Rating::whereHas('item', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        $adminEarnings = $orderItems->count();

        return (int) $adminEarnings;
    }

    private function calculateRevenues($startDate, $endDate)
    {
        // Calculate and return revenues for the specified date range (e.g., hourly, daily, monthly)
        $vendorId = Auth::user()->vendor->id;
        $orderItems = OrderItem::whereHas('item', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'rejected')
            ->where('status', '!=', 'unpaid')
            ->get();

        $totalEarnings = $orderItems->sum(function ($orderItem) {
            return $orderItem->price * $orderItem->quantity;
        });

        return (int) $totalEarnings;
    }


    private function calculatePayouts($startDate, $endDate)
    {
        // Calculate and return revenues for the specified date range (e.g., hourly, daily, monthly)
        $vendorId = Auth::user()->vendor->id;
        $payouts = Payout::where('vendor_id', $vendorId)->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'rejected')
            ->get();

        $totalPayouts = $payouts->sum(function ($payout) {
            return $payout->request_amount;
        });

        return (int) $totalPayouts;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function report()
    {
        $vendorId = Auth::user()->vendor->id;
        // Define the statuses you want to count
        $statuses = ['delivered', 'rejected', 'accepted', 'completed'];

        // Initialize an array to hold the counts for each status
        $statusCounts = [];

        // Loop through each status and count the corresponding OrderItems
        foreach ($statuses as $status) {
            $count = OrderItem::whereHas('item', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
                ->where('status', $status)
                ->count();

            // Store the count in the statusCounts array with the status as the key
            $statusCounts[] = $count;
        }
        return view('pages.report.vendor.report', compact('statusCounts'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }
    public function settings()
    {
        return view('pages.customers.vendor.settings');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'brand_name' => 'required|string|max:255|unique:vendors,brand_name,' . $user->vendor->id . ',id',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'fassi_no' => 'nullable|string|max:255',
            'stall_no' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:png,jpg,jpeg|max:2048', // max 2MB
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->vendor->brand_name = $request->input('brand_name');
        $user->vendor->fassi_no = $request->input('fassi_no');
        $user->vendor->stall_no = $request->input('stall_no');

        if ($request->hasFile('avatar')) {
            if ($user->vendor->avatar) {
                File::delete($user->vendor->avatar);
            }
            $image = $request->file('avatar');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images/users', $imageName, 'public');
            $imagePath = 'storage/' . $imagePath;
            $user->vendor->avatar = $imagePath;
        }

        $user->save();
        $user->vendor->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function getDataItemTotalOrder($duration)
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

        // Initialize labels
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $labels[] = $currentDate->format($labelFormat);
            $currentDate->add($interval);
        }

        // Initialize series data with zeros for each item of the vendor
        $vendorId = auth()->user()->vendor->id; // Assuming the vendor is logged in
        $allItems = DB::table('items')
            ->where('vendor_id', $vendorId)
            ->pluck('item_name')
            ->toArray();

        foreach ($allItems as $item) {
            $seriesData[$item] = array_fill(0, count($labels), 0);
        }

        // Fill series data with actual counts
        $currentDate = $startDate;
        $labelIndex = 0;

        while ($currentDate <= $endDate) {
            $nextDate = $currentDate->copy()->add($interval);
            $itemSales = $this->calculateItemSales($vendorId, $currentDate, $nextDate); // Assuming this method exists
            foreach ($itemSales as $sale) {
                $seriesData[$sale->name][$labelIndex] = $sale->data;
            }
            $currentDate = $nextDate;
            $labelIndex++;
        }

        // Convert series data to correct format
        foreach ($seriesData as $item => $data) {
            $series[] = [
                'name' => $item,
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

    // Assuming this method exists to calculate item sales for the given period
    private function calculateItemSales($vendorId, $startDate, $endDate)
    {
        return DB::table('order_items')
            ->join('items', 'order_items.item_id', '=', 'items.id')
            ->where('order_items.status', '!=', 'unpaid')
            ->where('items.vendor_id', $vendorId)
            ->whereBetween('order_items.created_at', [$startDate, $endDate])
            ->select('items.item_name as name', DB::raw('SUM(order_items.quantity) as data'))
            ->groupBy('items.item_name')
            ->get()
            ->toArray();
    }
}

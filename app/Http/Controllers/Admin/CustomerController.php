<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PointsCreditEmail;
use App\Models\Customer;
use App\Models\CustomerPoint;
use App\Models\CustomerPointLog;
use App\Models\Notification;
use App\Models\Point;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = User::role('customer')->get();
        return view('pages.customers.admin.customer', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'required|phone|unique:users,phone',
        ], [
            'phone.phone' => 'The :attribute field must be a valid number.',
        ]);

        // Create a new user
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => bcrypt(Str::random(8)),
        ])->assignRole('customer');

        // Create a new customer for the user
        Customer::create([
            'user_id' => $user->id,
            'date_of_birth' => $request->input('date_of_birth'),
        ]);

        CustomerPoint::create([
            'user_id' => $user->id,
            'points' => Point::first()->signup_points['points']
        ]);

        return response()->json(['success' => true, 'message' => 'Customer created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('pages.customers.admin.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('pages.customers.admin.show', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'email' => 'nullable|email|unique:users,email,' . $user->id . ',id',
            'phone' => 'required|phone:BD,IN|unique:users,phone,' . $user->id . ',id',
        ], [
            'phone.phone' => 'The :attribute field must be a valid number.',
        ]);

        $customer = $user->customer;

        // Update customer data
        $customer->date_of_birth = $request->input('date_of_birth');

        // Update user data
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');

        $customer->save();
        $user->save();

        return redirect('/dashboard/customers/' . $user->id . '/edit')->with('success', 'Customer updated successfully');
    }

    public function updateWithPoints(Request $request, $id)
    {
        // Find the user and customer
        $user = User::findOrFail($id);
        $customer = $user->customer;
        // Validate request data
        $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'email' => 'nullable|email|unique:users,email,' . $user->id . ',id',
            'phone' => 'required|phone:BD,IN|unique:users,phone,' . $user->id . ',id',
            'points' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($request) {
                    $user = User::find($request->user_id);
                    if ($user && ($value + $user->point->points < 0)) {
                        $fail('The points must be greater than the current points of the selected user.');
                    }
                },
            ],
        ], [
            'phone.phone' => 'The :attribute field must be a valid number.',
        ]);
        // Begin database transaction
        DB::beginTransaction();

        try {
            // Update customer data
            $customer->date_of_birth = $request->input('date_of_birth');
            $customer->save();

            // Update user data
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            $user->save();

            // Update customer points
            $customerPoint = CustomerPoint::where('user_id', $id)->first();
            if ($customerPoint) {
                $customerPoint->points = $customerPoint->points +  $request->input('points');
                $customerPoint->update();
            } else {
                // Create new CustomerPoint record if not found
                $customerPoint = new CustomerPoint();
                $customerPoint->user_id = $id;
                $customerPoint->points = $request->input('points');
                $customerPoint->save();
            }

            if ($request->input('points') != 0) {
                CustomerPointLog::create([
                    'user_id' => $user->id,
                    'action' => $request->input('points') > 0 ? 'Bonus' : 'Penalty',
                    'points' => abs($request->input('points')),
                    'details' => $request->input('points') > 0 ? 'Added By admin' : "Reduced By admin",
                ]);
                if ($request->input('points') > 0) {
                    Notification::create([
                        'user_id' => $user->id,
                        'message' => $request->message ?? 'Congratulations! You got ' . $request->input('points') . ' points',
                    ]);
                } elseif ($request->input('points') < 0) {
                    Notification::create([
                        'user_id' => $user->id,
                        'message' => $request->message ?? abs($request->input('points')) . ' points have been deducted from your account.',
                        'action' => 'debited'
                    ]);
                }

                try {
                    if ($user->email && $request->input('points') > 0) {
                        Mail::to($user->email)->send(new PointsCreditEmail($user->id, $request->input('points'), $request->message));
                    }
                } catch (\Exception $e) {
                    // Log the exception or perform any other error handling
                    Log::error('Failed to send email: ' . $e->getMessage());
                    dd($e->getMessage());
                }
            }

            // Commit database transaction
            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Roll back database transaction on error
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update customer: ' . $e->getMessage());
        }
    }

    public function editPoints(Request $request, $id)
    {
        $request->validate([
            'points' => 'required|numeric',
        ]);

        $customerPoint = CustomerPoint::where('user_id', $id)->first();

        $customerPoint->points = $request->points;
        $customerPoint->save();
        return redirect('/dashboard/customers/' . $id)->with('success', 'Customer points updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->customer->delete();

        $user->delete();

        return redirect('/dashboard/customers')->with('success', 'Customer deleted successfully.');
    }

    public function getCustomerDrawer(Request $request)
    {
        $customerId = $request->input('id');

        // Retrieve customer data based on customer ID
        $customer = User::find($customerId);

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        $totalExpenses = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.user_id', $customerId)
            ->where('order_items.status', '!=', 'rejected')
            ->sum(DB::raw('order_items.price * order_items.quantity'));

        // Render the drawer card view with customer data
        $drawerContent = view('pages.customers.admin.partials.customerViewDrawerCard', ['customer' => $customer, 'totalExpenses' => $totalExpenses])->render();

        // Return the rendered drawer card HTML as part of the AJAX response
        return response()->json(['drawerContent' => $drawerContent]);
    }
}

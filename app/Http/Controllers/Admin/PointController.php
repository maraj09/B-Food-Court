<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PointsCreditEmail;
use App\Models\CustomerPointLog;
use App\Models\Notification;
use App\Models\Point;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $points =  Point::first();

        if (!$points) {
            $points = Point::create([
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
        } else {
            // Check and add play_area_points if missing
            if (is_null($points->play_area_points)) {
                $points->play_area_points = [
                    'points' => 0,
                    'alert_message' => 'Alert',
                    'status' => 'active'
                ];
            }

            // Check and add event_points if missing
            if (is_null($points->event_points)) {
                $points->event_points = [
                    'points' => 0,
                    'alert_message' => 'Alert',
                    'status' => 'active'
                ];
            }

            // Save the updated points object
            $points->save();
        }

        return view('pages.points.admin.points', compact('points'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function updateValue(Request $request)
    {
        $request->validate([
            'value' => 'required|numeric', // Add any additional validation rules as needed
        ]);

        $newValue = $request->input('value');
        $point = Point::first(); // Find or create new if not found

        // Update the value
        $point->value = $newValue;
        $point->save();

        return response()->json(['success' => true]);
    }

    public function updateMinimumAmount(Request $request)
    {
        $request->validate([
            'value' => 'required|numeric', // Add any additional validation rules as needed
        ]);

        $newValue = $request->input('value');
        $point = Point::first(); // Find or create new if not found

        // Update the value
        $point->minimum_amount = $newValue;
        $point->save();

        return response()->json(['success' => true]);
    }

    public function updateMaxPoint(Request $request)
    {
        $request->validate([
            'value' => 'required|numeric', // Add any additional validation rules as needed
        ]);

        $newValue = $request->input('value');
        $point = Point::first(); // Find or create new if not found

        // Update the value
        $point->max_points = $newValue;
        $point->save();

        return response()->json(['success' => true]);
    }

    public function updateSignupPoints(Request $request)
    {

        $request->validate([
            'points' => 'required|integer',
            'alert_message' => 'required|string',
            'status' => 'required|in:active,inactive'
        ]);

        $points = Point::firstOrFail();
        $points->signup_points = [
            'points' => $request->input('points'),
            'alert_message' => $request->input('alert_message'),
            'status' => $request->input('status')
        ];
        $points->save();

        return response()->json(['success' => true]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function updateSignupStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $point = Point::firstOrFail(); // Assuming you're updating the record with ID 1, adjust as needed

        // Update the status in the previous JSON array with the new status value
        $previousJsonArray = $point->signup_points; // Assuming signup_points is the column storing the JSON array
        $previousJsonArray['status'] = $request->input('status');

        // Save the updated JSON array back to the database
        $point->signup_points = $previousJsonArray;
        $point->save();

        // Return a response
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    public function updatePlayAreaPoints(Request $request)
    {

        $request->validate([
            'points' => 'required|integer',
            'alert_message' => 'required|string',
            'status' => 'required|in:active,inactive'
        ]);

        $points = Point::firstOrFail();
        $points->play_area_points = [
            'points' => $request->input('points'),
            'alert_message' => $request->input('alert_message'),
            'status' => $request->input('status')
        ];
        $points->save();

        return response()->json(['success' => true]);
    }

    public function updatePlayAreaStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $point = Point::firstOrFail(); // Assuming you're updating the record with ID 1, adjust as needed

        // Update the status in the previous JSON array with the new status value
        $previousJsonArray = $point->play_area_points; // Assuming play_area_points is the column storing the JSON array
        $previousJsonArray['status'] = $request->input('status');

        // Save the updated JSON array back to the database
        $point->play_area_points = $previousJsonArray;
        $point->save();

        // Return a response
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    public function updateEventPoints(Request $request)
    {

        $request->validate([
            'points' => 'required|integer',
            'alert_message' => 'required|string',
            'status' => 'required|in:active,inactive'
        ]);

        $points = Point::firstOrFail();
        $points->event_points = [
            'points' => $request->input('points'),
            'alert_message' => $request->input('alert_message'),
            'status' => $request->input('status')
        ];
        $points->save();

        return response()->json(['success' => true]);
    }

    public function updateEventStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $point = Point::firstOrFail(); // Assuming you're updating the record with ID 1, adjust as needed

        // Update the status in the previous JSON array with the new status value
        $previousJsonArray = $point->event_points; // Assuming event_points is the column storing the JSON array
        $previousJsonArray['status'] = $request->input('status');

        // Save the updated JSON array back to the database
        $point->event_points = $previousJsonArray;
        $point->save();

        // Return a response
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function updateLoginPoints(Request $request)
    {
        // Validation (customize as per your requirements)
        $request->validate([
            'points' => 'required|integer',
            'logins' => 'required|integer',
            'limit' => 'required', // Adjust as per your requirements
            'alert_message' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $points = Point::firstOrFail();
        $points->login_points = [
            'points' => $request->points,
            'logins' => $request->logins,
            'limit' => $request->limit,
            'alert_message' => $request->alert_message,
            'status' => $request->status,
        ];

        $points->save();

        return response()->json(['success' => true, 'message' => 'Login Points updated successfully']);
    }

    public function updateLoginStatus(Request $request)
    {
        // Validation (customize as per your requirements)
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $point = Point::firstOrFail(); // Assuming you're updating the record with ID 1, adjust as needed

        // Update the status in the previous JSON array with the new status value
        $previousJsonArray = $point->login_points; // Assuming signup_points is the column storing the JSON array
        $previousJsonArray['status'] = $request->input('status');

        // Save the updated JSON array back to the database
        $point->login_points = $previousJsonArray;
        $point->save();

        // Return a response
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }


    public function updateOrderPoints(Request $request)
    {
        // Validation (customize as per your requirements)
        $request->validate([
            'points' => 'required|integer',
            'alert_message' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $points = Point::firstOrFail(); // Assuming you're updating the record with ID 1, adjust as needed
        $points->order_points = [
            'points' => $request->points,
            'alert_message' => $request->alert_message,
            'status' => $request->status,
        ];

        $points->save();

        return response()->json(['success' => true, 'message' => 'Order Points updated successfully']);
    }

    public function updateOrderStatus(Request $request)
    {
        // Validation (customize as per your requirements)
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $point = Point::firstOrFail(); // Assuming you're updating the record with ID 1, adjust as needed

        // Update the status in the previous JSON array with the new status value
        $previousJsonArray = $point->order_points; // Assuming order_points is the column storing the JSON array
        $previousJsonArray['status'] = $request->input('status');

        // Save the updated JSON array back to the database
        $point->order_points = $previousJsonArray;
        $point->save();

        // Return a response
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    public function updateReviewAndRatingsPoints(Request $request)
    {
        // Validation (customize as per your requirements)
        $request->validate([
            'review_points' => 'required|integer',
            'ratings_points' => 'required|integer',
            'alert_message' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $points = Point::firstOrFail(); // Assuming you're updating the record with ID 1, adjust as needed
        $points->review_points = [
            'review_points' => $request->review_points,
            'ratings_points' => $request->ratings_points,
            'alert_message' => $request->alert_message,
            'status' => $request->status,
        ];

        $points->save();

        return response()->json(['success' => true, 'message' => 'Review & Ratings Points updated successfully']);
    }

    public function updateReviewAndRatingsStatus(Request $request)
    {
        // Validation (customize as per your requirements)
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $point = Point::firstOrFail(); // Assuming you're updating the record with ID 1, adjust as needed

        // Update the status in the previous JSON array with the new status value
        $previousJsonArray = $point->review_points; // Assuming review_and_ratings_points is the column storing the JSON array
        $previousJsonArray['status'] = $request->input('status');

        // Save the updated JSON array back to the database
        $point->review_points = $previousJsonArray;
        $point->save();

        // Return a response
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    public function updateBirthdayPoints(Request $request)
    {
        // Validation (customize as per your requirements)
        $request->validate([
            'points' => 'required|integer',
            'alert_message' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $points = Point::firstOrFail(); // Assuming you're updating the record with ID 1, adjust as needed
        $points->birthday_points = [
            'points' => $request->points,
            'alert_message' => $request->alert_message,
            'status' => $request->status,
        ];

        $points->save();

        return response()->json(['success' => true, 'message' => 'Birthday Points updated successfully']);
    }

    public function updateBirthdayStatus(Request $request)
    {
        // Validation (customize as per your requirements)
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $point = Point::firstOrFail(); // Assuming you're updating the record with ID 1, adjust as needed

        // Update the status in the previous JSON array with the new status value
        $previousJsonArray = $point->birthday_points; // Assuming birthday_points is the column storing the JSON array
        $previousJsonArray['status'] = $request->input('status');

        // Save the updated JSON array back to the database
        $point->birthday_points = $previousJsonArray;
        $point->save();

        // Return a response
        return response()->json(['success' => true, 'message' => 'Birthday Status updated successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function logIndex()
    {
        $pointLogs = CustomerPointLog::orderByDesc('created_at')->get();
        $customers = User::role('customer')->get();
        $totalPointEarns = $pointLogs->sum(function ($log) {
            if ($log->action != 'Redeem' && $log->action != 'Penalty') {
                return $log->points;
            }
        });
        $totalPointRedeems = $pointLogs->sum(function ($log) {
            if ($log->action == 'Redeem' || $log->action == 'Penalty') {
                return $log->points;
            }
        });
        return view('pages.pointLog.pointLog', compact(['pointLogs', 'customers', 'totalPointEarns', 'totalPointRedeems']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function logStore(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
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
            'details' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($request->user_id);

        $point = $user->point->points +  $request->points;

        $user->point->points = $point;

        $user->point->save();

        if ($request->input('points') > 0) {
            CustomerPointLog::create([
                'user_id' => $user->id,
                'action' => 'Bonus',
                'points' => $request->input('points'),
                'details' => $request->input('details')
            ]);
            Notification::create([
                'user_id' =>  $user->id,
                'message' => $request->input('details'),
            ]);
            try {
                if ($user->email && $request->input('points') > 0) {
                    Mail::to(auth()->user()->email)->send(new PointsCreditEmail($user->id, $request->input('points'), $request->details));
                }
            } catch (\Exception $e) {
                // Log the exception or perform any other error handling
                Log::error('Failed to send email: ' . $e->getMessage());
            }
        }

        if ($request->input('points') < 0) {
            CustomerPointLog::create([
                'user_id' => $user->id,
                'action' => 'Penalty',
                'points' => abs($request->input('points')),
                'details' => $request->input('details')
            ]);
            Notification::create([
                'user_id' => $user->id,
                'message' => $request->input('details') ?? abs($request->input('points')) . ' points have been deducted from your account.',
                'action' => 'debited'
            ]);
        }

        return back()->with('success', 'Customer points updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

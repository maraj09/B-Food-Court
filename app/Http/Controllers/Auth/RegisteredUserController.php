<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeEmail;
use App\Models\Customer;
use App\Models\CustomerPoint;
use App\Models\CustomerPointLog;
use App\Models\Notification;
use App\Models\Point;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Kreait\Firebase\Factory;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ])->assignRole('admin');
    }

    public function verification(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'unique:' . User::class, 'phone:BD,IN'],
            'email' => ['nullable', 'unique:' . User::class],
            'date_of_birth' => ['required'],
        ], [
            'phone.phone' => 'The :attribute field must be a valid number.',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'unique:' . User::class, 'phone:BD,IN'],
            'email' => ['nullable', 'unique:' . User::class],
            'date_of_birth' => ['required'],
            'firebaseIdToken' => ['required', 'string'],
        ], [
            'phone.phone' => 'The :attribute field must be a valid number.',
        ]);

        $auth = Firebase::auth();

        try {
            $verifiedIdToken = $auth->verifyIdToken($request->firebaseIdToken);
        } catch (FailedToVerifyToken $e) {
            return response()->json(['success' => false, 'message' => 'The token is invalid: ' . $e->getMessage()]);
        }

        $uid = $verifiedIdToken->claims()->get('sub');
        $user = $auth->getUser($uid);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'The token is invalid']);
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make(Str::random(8)),
            'phone_verified_at' => now(),
        ])->assignRole('customer');

        Customer::create([
            'user_id' => $user->id,
            'date_of_birth' => $request->date_of_birth,
        ]);

        $customerPoint = CustomerPoint::where('user_id', $user->id)->first();

        if (!$customerPoint) {
            $points = Point::first();
            if ($points->signup_points['status'] == 'active') {
                $signup_points = $points->signup_points['points'];
            } else {
                $signup_points = 0;
            }
            CustomerPoint::create([
                'user_id' => $user->id,
                'points' => $signup_points
            ]);
            if ($points->signup_points['points'] > 0 && $points->signup_points['status'] == 'active') {
                CustomerPointLog::create([
                    'user_id' => $user->id,
                    'action' => 'Signup',
                    'points' => $points->signup_points['points'],
                    'details' =>  'Welcome ' . $points->signup_points['points'] . ' Points added'
                ]);
                Notification::create([
                    'user_id' => $user->id,
                    'message' => $points->signup_points['alert_message'],
                ]);
            }
        }

        try {
            if ($user->email) {
                Mail::to($user->email)->send(new WelcomeEmail($user->id));
            }
        } catch (\Exception $e) {
            // Log the exception or perform any other error handling
            Log::error('Failed to send email: ' . $e->getMessage());
        }

        event(new Registered($user));

        Auth::login($user);


        return response()->json(['success' => true, 'message' => 'Registration Successful!']);
    }

    public function storeAsGuest(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'unique:' . User::class, 'phone:BD,IN'],
            'firebaseIdToken' => ['required', 'string'],
        ], [
            'phone.phone' => 'The :attribute field must be a valid number.',
        ]);

        $auth = Firebase::auth();

        try {
            $verifiedIdToken = $auth->verifyIdToken($request->firebaseIdToken);
        } catch (FailedToVerifyToken $e) {
            return response()->json(['success' => false, 'message' => 'The token is invalid: ' . $e->getMessage()]);
        }

        $uid = $verifiedIdToken->claims()->get('sub');
        $user = $auth->getUser($uid);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'The token is invalid']);
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make(Str::random(8)),
            'phone_verified_at' => now(),
        ])->assignRole('customer');

        Customer::create([
            'user_id' => $user->id,
        ]);

        $customerPoint = CustomerPoint::where('user_id', $user->id)->first();

        if (!$customerPoint) {
            $points = Point::first();
            if ($points->signup_points['status'] == 'active') {
                $signup_points = $points->signup_points['points'];
            } else {
                $signup_points = 0;
            }
            CustomerPoint::create([
                'user_id' => $user->id,
                'points' => $signup_points
            ]);
            if ($points->signup_points['points'] > 0 && $points->signup_points['status'] == 'active') {
                CustomerPointLog::create([
                    'user_id' => $user->id,
                    'action' => 'Signup',
                    'points' => $points->signup_points['points'],
                    'details' =>  'Welcome ' . $points->signup_points['points'] . ' Points added'
                ]);
                Notification::create([
                    'user_id' => $user->id,
                    'message' => $points->signup_points['alert_message'],
                ]);
            }
        }

        try {
            if ($user->email) {
                Mail::to($user->email)->send(new WelcomeEmail($user->id));
            }
        } catch (\Exception $e) {
            // Log the exception or perform any other error handling
            Log::error('Failed to send email: ' . $e->getMessage());
        }

        event(new Registered($user));

        Auth::login($user);

        return response()->json(['success' => true, 'message' => 'Registration Successful!']);
    }
}

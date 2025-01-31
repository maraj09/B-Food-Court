<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Jobs\SendWelcomeEmail;
use App\Mail\PointsCreditEmail;
use App\Mail\WelcomeEmail;
use App\Models\Customer;
use App\Models\CustomerPoint;
use App\Models\CustomerPointLog;
use App\Models\LoginLog;
use App\Models\Notification;
use App\Models\Point;
use App\Models\Setting;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Illuminate\Support\Str;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // $settings = Setting::first();
        // dd(json_decode($settings->company_contact_phone, true));
        return view('auth.login');
    }

    public function adminCreate(): View
    {
        return view('auth.adminLogin');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        if (User::find(auth()->user()->id)->hasRole('vendor')) {
            return;
        } else if (User::find(auth()->user()->id)->hasRole('customer')) {
            $this->customerUpdateInfo();
        } else {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $permissions = ['cashier-play-area-management', 'cashier-events-management', 'cashier-items-management'];
            $hasOnlySpecifiedPermissions = $user->roles()->first()->permissions->pluck('name')->diff($permissions)->isEmpty();
            if ($user->hasAnyPermission($permissions) && $hasOnlySpecifiedPermissions) {
                return redirect('/dashboard/orders/create');
            }
            return redirect(RouteServiceProvider::DASHBOARD_HOME);
        }

        return;
    }

    public function getLoginCounts($userId, $period)
    {
        // Get the start and end dates for the specified period
        $startDate = Carbon::now();
        $endDate = Carbon::now();
        if ($period === 'total') {
            return LoginLog::where('user_id', $userId)->count();
        }
        switch ($period) {
            case 'today':
                $startDate->startOfDay();
                $endDate->endOfDay();
                break;
            case 'week':
                $startDate->startOfWeek();
                $endDate->endOfWeek();
                break;
            case 'month':
                $startDate->startOfMonth();
                $endDate->endOfMonth();
                break;
            default:
                // Default to today
                $startDate->startOfDay();
                $endDate->endOfDay();
                break;
        }

        $loginLogs = LoginLog::where('user_id', $userId)
            ->whereBetween('login_time', [$startDate, $endDate])
            ->get();

        $loginCount = $loginLogs->count();

        return $loginCount;
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $lastLoginLog = LoginLog::where('user_id', Auth::id())
            ->latest('login_time')
            ->first();

        // $user = Auth::user();
        // $user->onesignal_subs_id = null;
        // /** @var \App\Models\User $user */
        // $user->save();

        if ($lastLoginLog) {
            $lastLoginLog->update([
                'logout_time' => now(),
            ]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function verification(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'exists:users,phone'],
        ], [
            'phone.exists' => trans('auth.failed')
        ]);
    }

    public function loginSignupVerification(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'phone:BD,IN'],
        ], [
            'phone.phone' => 'The :attribute field must be a valid number.',
        ]);
        $request->validate(['phone' => 'required|string']);

        $user = User::where('phone', $request->phone)->first();

        if ($user) {
            return response()->json(['data' =>  'login']);
        }

        return response()->json(['data' =>  'signup']);
    }

    public function OtpLessSignIn(Request $request)
    {
        $response = Http::asForm()->post('https://auth.otpless.app/auth/userInfo', [
            'token' => $request->otpless_user_token,
            'client_id' => env('OTP_LESS_CLIENT_ID'),
            'client_secret' => env('OTP_LESS_CLIENT_SECRET'),
        ]);

        // Handle the response
        if ($response->successful()) {
            $data = $response->json();
            // Do something with the data
        } else {
            // Handle the error
            $error = $response->body();
            // Log or return the error message
        }
        if (array_key_exists('message', $data)) {
            return redirect()->back()->with('error', $data['message']);
        }

        if ($data['authentication_details']['email']) {
            $name = $data['name'] == '' ? 'customer' . rand(1000, 9999) : $data['name'];
            $email = $data['authentication_details']['email']['email'];

            $existingUser = User::where('email', $email)->first();

            if ($existingUser) {
                Auth::login($existingUser);
            } else {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make(Str::random(8)),
                    'email_verified_at' => now(),
                ])->assignRole('customer');
            }
        }

        if ($data['authentication_details']['phone']) {
            $name = $data['name'] == '' ? 'customer' . rand(1000, 9999) : $data['name'];
            $phone = $data['country_code'] . $data['national_phone_number'];
            $existingUser = User::where('phone', $phone)->first();

            if ($existingUser) {
                Auth::login($existingUser);
            } else {
                $user = User::create([
                    'name' => $name,
                    'phone' => $phone,
                    'password' => Hash::make(Str::random(8)),
                    'phone_verified_at' => now(),
                ])->assignRole('customer');
            }
        }

        if (!$existingUser) {
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
        }

        if (User::find(auth()->user()->id)->hasRole('customer')) {
            $this->customerUpdateInfo();
        }

        return response()->json(['success' =>  true]);
    }

    public function customerUpdateInfo()
    {
        LoginLog::create([
            'user_id' => Auth::id(),
            'login_time' => now(),
        ]);

        $period = Point::first()->login_points['limit'];
        $limits = Point::first()->login_points['logins'];
        $points = Point::first()->login_points['points'];
        $status = Point::first()->login_points['status'];

        $loginCount = $this->getLoginCounts(Auth::id(), $period);

        $pre_points = CustomerPoint::where('user_id', Auth::id())->first();
        if ($pre_points) {
            if ($loginCount <= $limits) {
                if ($status == 'active') {
                    $pre_points->points = $pre_points->points + $points;
                    $pre_points->save();
                }
                if ($points > 0 && $status == 'active') {
                    CustomerPointLog::create([
                        'user_id' => Auth::id(),
                        'action' => 'Login',
                        'points' => $points,
                        'details' =>  $points . ' points added for coming back again'
                    ]);
                    Notification::create([
                        'user_id' => Auth::id(),
                        'message' => Point::first()->login_points['alert_message'],
                    ]);
                    try {
                        if (auth()->user()->email) {
                            Mail::to(auth()->user()->email)->send(new PointsCreditEmail(auth()->user()->id, $points, Point::first()->login_points['alert_message']));
                        }
                    } catch (\Exception $e) {
                        // Log the exception or perform any other error handling
                        Log::error('Failed to send email: ' . $e->getMessage());
                    }
                }
            }
        } else {
            CustomerPoint::create([
                'user_id' =>  Auth::id(),
                'points' =>  $status == 'active' ? $points : 0,
            ]);
            if ($points > 0 && $status == 'active') {
                CustomerPointLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'Login',
                    'points' => $points,
                    'details' =>  $points . ' points added for coming back again'
                ]);
                Notification::create([
                    'user_id' => Auth::id(),
                    'message' => Point::first()->login_points['alert_message'],
                ]);
                try {
                    if (auth()->user()->email) {
                        Mail::to(auth()->user()->email)->send(new PointsCreditEmail(auth()->user()->id, $points, Point::first()->login_points['alert_message']));
                    }
                } catch (\Exception $e) {
                    // Log the exception or perform any other error handling
                    Log::error('Failed to send email: ' . $e->getMessage());
                }
            }
        }
    }
}

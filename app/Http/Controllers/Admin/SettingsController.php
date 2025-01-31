<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvoiceTax;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings =  Setting::firstOrCreate();

        return view('pages.settings.admin.settings', compact('settings'));
    }

    public function notificationIndex()
    {
        $settings =  Setting::firstOrCreate();

        return view('pages.settings.admin.notificationSettings', compact('settings'));
    }

    public function footerIndex()
    {
        $settings =  Setting::firstOrCreate();

        return view('pages.settings.admin.footerSettings', compact('settings'));
    }

    public function paymentIndex()
    {
        $settings =  Setting::firstOrCreate();
        $invoiceTaxes = InvoiceTax::all();
        return view('pages.settings.admin.paymentSettings', compact('settings', 'invoiceTaxes'));
    }

    public function pageIndex()
    {
        $settings =  Setting::firstOrCreate();

        return view('pages.settings.admin.pageSettings', compact('settings'));
    }

    public function modulesIndex()
    {
        $settings =  Setting::firstOrCreate();

        return view('pages.settings.admin.modules-settings', compact('settings'));
    }

    public function authenticationIndex()
    {
        $settings =  Setting::firstOrCreate();

        return view('pages.settings.admin.authentication-settings', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'project_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'project_favicon_icon' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'website_name' => 'nullable|string|max:255',
            'website_title' => 'nullable|string|max:255',
            'meta_desc' => 'nullable|string|max:255',
            'web_version' => 'nullable|string|max:10',
            'web_version_date' => 'nullable|date',
            'status' => 'nullable|boolean',
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer',
            'email' => 'nullable|email|max:255',
            'api_id' => 'nullable|string|max:255',
            'key' => 'nullable|string|max:255',
            'secret' => 'nullable|string|max:255',
            'wp_phone_number_id' => 'nullable|string|max:255',
            'wp_business_account_id' => 'nullable|string|max:255',
            'permanent_access_token' => 'nullable|string|max:255',
            'businesses' => 'nullable|integer',
            'members' => 'nullable|integer',
            'events' => 'nullable|integer',
            'our_clients' => 'nullable|string|max:255',
            'footer_logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'footer_banner_heading' => 'nullable|string',
            'footer_banner_sub_heading' => 'nullable|string',
            'footer_banner_sub_heading_url' => 'nullable|string',
            'footer_desc' => 'nullable|string',
            'company_contact_email' => 'nullable|string|email',
            'company_contact_phone' => 'nullable|string',
            'company_address' => 'nullable|string',
            'company_address_map_link' => 'nullable|string',
            'facebook' => 'nullable|string',
            'instagram' => 'nullable|string',
            'twitter' => 'nullable|string',
            'youtube' => 'nullable|string',
            'whatsapp' => 'nullable|string',
            'linkedin' => 'nullable|string',
            'copyright' => 'nullable|string',
            'status' => 'nullable|boolean',
            'gst' => 'nullable|numeric|min:0',
            'sgt' => 'nullable|numeric|min:0',
            'service_tax' => 'nullable|numeric|min:0',
            'payment_mode_upi_status' => 'nullable|boolean',
            'payment_mode_cash_status' => 'nullable|boolean',
            'payment_mode_card_status' => 'nullable|boolean',
            'items_status' => 'nullable|boolean',
            'play_area_status' => 'nullable|boolean',
            'event_status' => 'nullable|boolean',
            'about_us' => 'nullable|string',
            'embed_map_link' => 'nullable|string',
            'contact_us_email' => 'nullable|string',
            'terms' => 'nullable|string',
            'refund' => 'nullable|string',
            'sign_in_method' => 'nullable|string',
        ]);

        // Create a new setting instance
        $setting = Setting::first();

        // Assign values from the form to the setting instance
        $validatedData['status'] = $request->status ? 1 : 0;
        $validatedData['payment_mode_upi_status'] = $request->payment_mode_upi_status ? 1 : 0;
        $validatedData['payment_mode_cash_status'] = $request->payment_mode_cash_status ? 1 : 0;
        $validatedData['payment_mode_card_status'] = $request->payment_mode_card_status ? 1 : 0;
        $validatedData['items_status'] = $request->items_status ? 1 : 0;
        $validatedData['play_area_status'] = $request->play_area_status ? 1 : 0;
        $validatedData['event_status'] = $request->event_status ? 1 : 0;
        $validatedData['gst_admin'] = $request->gst_admin ? 1 : 0;
        $validatedData['sgt_admin'] = $request->sgt_admin ? 1 : 0;
        $validatedData['gst'] = $request->gst ?? 0;
        $validatedData['sgt'] = $request->sgt ?? 0;
        $validatedData['service_tax'] = $request->service_tax ?? 0;

        if ($request->avatar_remove == 1) {
            // Delete the existing project logo if it exists
            if ($setting->project_logo) {
                File::delete($setting->project_logo);
                $setting->project_logo = null;
            }
        }

        if ($request->favicon_remove == 1) {
            // Delete the existing project logo if it exists
            if ($setting->project_favicon_icon) {
                File::delete($setting->project_favicon_icon);
                $setting->project_favicon_icon = null;
            }
        }


        // Handle image upload if provided
        if ($request->hasFile('project_logo')) {
            // Delete the existing project logo if it exists
            if ($setting->project_logo) {
                File::delete($setting->project_logo);
            }

            // Upload the new project logo
            $image = $request->file('project_logo');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images/logo', $imageName, 'public');
            $validatedData['project_logo'] = 'storage/' . $imagePath;
        }

        if ($request->hasFile('project_favicon_icon')) {
            // Delete the existing project logo if it exists
            if ($setting->project_favicon_icon) {
                File::delete($setting->project_favicon_icon);
            }

            // Upload the new project logo
            $image = $request->file('project_favicon_icon');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images/logo', $imageName, 'public');
            $validatedData['project_favicon_icon'] = 'storage/' . $imagePath;
        }

        if ($request->hasFile('footer_logo')) {
            // Delete the existing project logo if it exists
            if ($setting->footer_logo) {
                File::delete($setting->footer_logo);
            }
            // Upload the new project logo
            $image = $request->file('footer_logo');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images/logo', $imageName, 'public');
            $validatedData['footer_logo'] = 'storage/' . $imagePath;
        }

        // Save the setting instance
        $setting->update($validatedData);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Settings updated successfully!');
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
}

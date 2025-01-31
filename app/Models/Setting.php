<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $casts = [
        'company_contact_phone' => 'array',
    ];

    protected $fillable = [
        'project_logo',
        'website_name',
        'website_title',
        'meta_desc',
        'web_version',
        'web_version_date',
        'status',
        'smtp_host',
        'smtp_port',
        'email',
        'api_id',
        'api_key',
        'api_secret',
        'wp_phone_number_id',
        'wp_business_account_id',
        'permanent_access_token',
        'businesses',
        'members',
        'events',
        'our_clients',
        'footer_logo',
        'footer_banner_heading',
        'footer_banner_sub_heading',
        'footer_banner_sub_heading_url',
        'footer_desc',
        'company_contact_email',
        'company_contact_phone',
        'company_address',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'whatsapp',
        'linkedin',
        'copyright',
        'project_favicon_icon',
        'gst',
        'sgt',
        'service_tax',
        'payment_mode_upi_status',
        'payment_mode_cash_status',
        'payment_mode_card_status',
        'gst_admin',
        'sgt_admin',
        'company_address_map_link',
        'refund',
        'terms',
        'contact_us_email',
        'about_us',
        'embed_map_link',
        'items_status',
        'play_area_status',
        'event_status',
        'sign_in_method',
    ];
}

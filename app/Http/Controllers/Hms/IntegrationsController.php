<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Services\PaymentGatewayService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IntegrationsController extends Controller
{
    public function index(): View
    {
        $integrations = [
            'payment_gateways' => [
                'stripe' => !empty(config('services.stripe.secret')),
                'paypal' => !empty(config('services.paypal.client_id')),
                'mpesa' => !empty(config('services.mpesa.consumer_key')),
            ],
            'sms_configured' => app(SmsService::class)->isConfigured(),
        ];

        return view('hms.integrations.index', compact('integrations'));
    }
    
    public function paymentGateways(): View
    {
        $gateways = [
            'stripe' => [
                'configured' => !empty(config('services.stripe.secret')),
                'name' => 'Stripe',
            ],
            'paypal' => [
                'configured' => !empty(config('services.paypal.client_id')),
                'name' => 'PayPal',
            ],
            'mpesa' => [
                'configured' => !empty(config('services.mpesa.consumer_key')),
                'name' => 'M-Pesa',
            ],
        ];

        return view('hms.integrations.payment-gateways', compact('gateways'));
    }
    
    public function whatsapp(): View
    {
        $configured = !empty(config('services.whatsapp.token'));
        return view('hms.integrations.whatsapp', compact('configured'));
    }
    
    public function googleCalendar(): View
    {
        $configured = !empty(config('services.google.client_id'));
        return view('hms.integrations.google-calendar', compact('configured'));
    }
    
    public function automatedAlerts(): View
    {
        $smsConfigured = app(SmsService::class)->isConfigured();
        return view('hms.integrations.alerts', compact('smsConfigured'));
    }
    
    public function dataSync(): View
    {
        return view('hms.integrations.data-sync');
    }
}

<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IntegrationsController extends Controller
{
    public function index(): View
    {
        return view('hms.integrations.index');
    }
    
    public function paymentGateways(): View
    {
        return view('hms.integrations.payment-gateways');
    }
    
    public function whatsapp(): View
    {
        return view('hms.integrations.whatsapp');
    }
    
    public function googleCalendar(): View
    {
        return view('hms.integrations.google-calendar');
    }
    
    public function automatedAlerts(): View
    {
        return view('hms.integrations.alerts');
    }
    
    public function dataSync(): View
    {
        return view('hms.integrations.data-sync');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\AppointmentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App as AppFacade;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        // Real metrics from database
        try {
            $stats = [
                'patients' => \App\Models\Patient::count(),
                'doctors' => \App\Models\Doctor::count(),
                'nurses' => \App\Models\Nurse::where('is_active', true)->count(),
                'happyPatients' => \App\Models\Patient::count(), // Using total patients as proxy for happy patients
                'years' => 15, // Hospital established years - can be made dynamic from settings
            ];
            
            // Get featured doctors for homepage
            $featuredDoctors = \App\Models\Doctor::with('department')
                ->take(6)
                ->get();
        } catch (\Exception $e) {
            // Handle test environment where tables may not exist
            $stats = [
                'patients' => 0,
                'doctors' => 0,
                'nurses' => 0,
                'happyPatients' => 0,
                'years' => 15,
            ];
            $featuredDoctors = collect([]);
        }
            
        // Get recent testimonials
        try {
            $testimonials = \App\Models\Testimonial::where('is_active', true)
                ->latest()
                ->take(3)
                ->get();
        } catch (\Exception $e) {
            $testimonials = collect([]);
        }
            
        return view('site.home', compact('stats', 'featuredDoctors', 'testimonials'));
    }

    public function services(): View
    {
        return view('site.services');
    }

    public function doctors(): View
    {
        $doctors = \App\Models\Doctor::with('department')
            ->paginate(12);
            
        $departments = \App\Models\DoctorDepartment::all();
        
        return view('site.doctors', compact('doctors', 'departments'));
    }

    public function about(): View
    {
        $stats = [
            'patients' => \App\Models\Patient::count(),
            'doctors' => \App\Models\Doctor::count(),
            'nurses' => \App\Models\Nurse::where('is_active', true)->count(),
            'happyPatients' => \App\Models\Patient::count(),
            'years' => 15,
        ];
        return view('site.about', compact('stats'));
    }

    public function contact(): View
    {
        return view('site.contact');
    }

    public function submitContact(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:40',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:2000',
        ]);
        Enquiry::create($data);
        return back()->with('status', __('Thank you. We will contact you soon.'));
    }

    public function features(): View
    {
        return view('site.features');
    }

    public function bookAppointment(): View
    {
        return view('site.book-appointment');
    }

    public function submitAppointment(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_name' => 'required|string|max:120',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:40',
            'doctor_name' => 'nullable|string|max:120',
            'preferred_date' => 'required|date',
            'note' => 'nullable|string|max:1000',
            'is_existing_patient' => 'nullable|boolean',
        ]);
        $data['is_existing_patient'] = (bool)($data['is_existing_patient'] ?? false);
        AppointmentRequest::create($data);
        return back()->with('status', __('Appointment request submitted. We will confirm shortly.'));
    }

    public function switchLanguage(string $locale): RedirectResponse
    {
        session(['locale' => $locale]);
        AppFacade::setLocale($locale);
        return back();
    }
}



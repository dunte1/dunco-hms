<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\AppointmentRequest;
use App\Traits\SeoTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App as AppFacade;
use Illuminate\View\View;

class SiteController extends Controller
{
    use SeoTrait;

    public function home(): View
    {
        try {
            $stats = [
                'patients' => \App\Models\Patient::count(),
                'doctors' => \App\Models\Doctor::count(),
                'nurses' => \App\Models\Nurse::where('is_active', true)->count(),
                'happyPatients' => \App\Models\Patient::count(),
                'years' => 15,
            ];
            $featuredDoctors = \App\Models\Doctor::with('department')->take(6)->get();
        } catch (\Exception $e) {
            $stats = ['patients' => 0, 'doctors' => 0, 'nurses' => 0, 'happyPatients' => 0, 'years' => 15];
            $featuredDoctors = collect([]);
        }

        try {
            $testimonials = \App\Models\Testimonial::where('is_active', true)->latest()->take(3)->get();
        } catch (\Exception $e) {
            $testimonials = collect([]);
        }

        $seo = $this->getCmsSeo('home');
        $seo['schema'] = [
            '@context' => 'https://schema.org',
            '@type' => 'Hospital',
            'name' => config('app.name', 'Dunco Hospital'),
            'description' => $seo['description'],
            'url' => url('/'),
            'telephone' => \App\Models\SystemSetting::get('header_emergency_phone', '+254700000000'),
            'address' => ['@type' => 'PostalAddress', 'addressLocality' => \App\Models\SystemSetting::get('contact_city', 'Nairobi'), 'addressCountry' => 'KE'],
            'medicalSpecialty' => ['General Medicine', 'Surgery', 'Pediatrics', 'Obstetrics'],
        ];

        return view('site.home', compact('stats', 'featuredDoctors', 'testimonials', 'seo'));
    }

    public function services(): View
    {
        $seo = $this->getCmsSeo('services');
        $seo['schema'] = [
            '@context' => 'https://schema.org',
            '@type' => 'MedicalBusiness',
            'name' => config('app.name', 'Dunco Hospital') . ' Services',
            'description' => $seo['description'],
            'url' => url('/services'),
        ];
        return view('site.services', compact('seo'));
    }

    public function doctors(): View
    {
        $doctors = \App\Models\Doctor::with('department')->paginate(12);
        $departments = \App\Models\DoctorDepartment::all();

        $seo = $this->getCmsSeo('doctors');
        $seo['schema'] = [
            '@context' => 'https://schema.org',
            '@type' => 'MedicalBusiness',
            'name' => config('app.name', 'Dunco Hospital') . ' - Our Doctors',
            'description' => $seo['description'],
            'url' => url('/doctors'),
        ];
        return view('site.doctors', compact('doctors', 'departments', 'seo'));
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
        $seo = $this->getCmsSeo('about');
        return view('site.about', compact('stats', 'seo'));
    }

    public function contact(): View
    {
        $seo = $this->getCmsSeo('contact');
        $seo['schema'] = [
            '@context' => 'https://schema.org',
            '@type' => 'ContactPage',
            'name' => config('app.name', 'Dunco Hospital') . ' - Contact Us',
            'url' => url('/contact'),
        ];
        return view('site.contact', compact('seo'));
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
        $seo = $this->getCmsSeo('features');
        return view('site.features', compact('seo'));
    }

    public function bookAppointment(): View
    {
        $doctors = \App\Models\Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'consultation_fee']);
        $seo = $this->seo([
            'title' => 'Book Appointment - ' . config('app.name', 'Dunco Hospital'),
            'description' => 'Book an appointment online at ' . config('app.name', 'Dunco Hospital'). '. Choose your doctor and preferred date.',
            'keywords' => 'book appointment, hospital appointment, doctor appointment, online booking',
        ]);
        return view('site.book-appointment', compact('doctors', 'seo'));
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

<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialsController extends Controller
{
    public function index(): View
    {
        $testimonials = Testimonial::where('status', 'approved')
            ->orderBy('is_featured', 'desc')
            ->latest()
            ->paginate(6);

        $featuredTestimonials = Testimonial::where('status', 'approved')
            ->where('is_featured', true)
            ->latest()
            ->limit(3)
            ->get();

        return view('site.testimonials.index', compact('testimonials', 'featuredTestimonials'));
    }

    public function create(): View
    {
        return view('site.testimonials.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_name' => 'required|string',
            'patient_email' => 'nullable|email',
            'patient_phone' => 'nullable|string',
            'testimonial' => 'required|string|min:50',
            'rating' => 'required|integer|min:1|max:5',
            'treatment_received' => 'nullable|string',
            'doctor_name' => 'nullable|string',
            'patient_photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('patient_photo')) {
            $data['patient_photo'] = $request->file('patient_photo')->store('testimonials/photos');
        }

        Testimonial::create($data);

        return redirect()->route('testimonials.index')->with('status', 'Thank you for your testimonial! It will be reviewed before being published.');
    }

    // Admin CRUD Methods
    public function adminIndex(Request $request): View
    {
        $query = Testimonial::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('patient_name', 'like', '%' . $request->search . '%')
                  ->orWhere('testimonial', 'like', '%' . $request->search . '%')
                  ->orWhere('treatment_received', 'like', '%' . $request->search . '%');
            });
        }

        $testimonials = $query->latest()->paginate(15);

        return view('cms.testimonials.index', compact('testimonials'));
    }

    public function show(Testimonial $testimonial): View
    {
        return view('cms.testimonials.show', compact('testimonial'));
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('cms.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $data = $request->validate([
            'patient_name' => 'required|string',
            'patient_email' => 'nullable|email',
            'patient_phone' => 'nullable|string',
            'testimonial' => 'required|string|min:50',
            'rating' => 'required|integer|min:1|max:5',
            'treatment_received' => 'nullable|string',
            'doctor_name' => 'nullable|string',
            'patient_photo' => 'nullable|image|max:2048',
            'status' => 'required|in:pending,approved,rejected',
            'is_featured' => 'boolean',
        ]);

        if ($request->hasFile('patient_photo')) {
            // Delete old photo if exists
            if ($testimonial->patient_photo) {
                \Storage::delete($testimonial->patient_photo);
            }
            $data['patient_photo'] = $request->file('patient_photo')->store('testimonials/photos');
        }

        $data['is_featured'] = $request->has('is_featured');

        $testimonial->update($data);

        return redirect()->route('cms.testimonials.index')
            ->with('success', 'Testimonial updated successfully!');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        // Delete photo if exists
        if ($testimonial->patient_photo) {
            \Storage::delete($testimonial->patient_photo);
        }

        $testimonial->delete();

        return redirect()->route('cms.testimonials.index')
            ->with('success', 'Testimonial deleted successfully!');
    }
}

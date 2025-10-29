<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CmsController extends Controller
{
    public function index(): View
    {
        return view('cms.admin.index');
    }
    
    public function homePage(): View
    {
        $content = SystemSetting::get('cms_home_content', '');
        $metaTitle = SystemSetting::get('cms_home_meta_title', 'Home - ' . config('app.name'));
        $metaDescription = SystemSetting::get('cms_home_meta_description', '');
        $metaKeywords = SystemSetting::get('cms_home_meta_keywords', '');
        
        return view('cms.admin.pages.home', compact('content', 'metaTitle', 'metaDescription', 'metaKeywords'));
    }
    
    public function updateHomePage(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ]);
        
        SystemSetting::set('cms_home_content', $validated['content'] ?? '');
        SystemSetting::set('cms_home_meta_title', $validated['meta_title'] ?? '');
        SystemSetting::set('cms_home_meta_description', $validated['meta_description'] ?? '');
        SystemSetting::set('cms_home_meta_keywords', $validated['meta_keywords'] ?? '');
        
        return redirect()->route('cms.home')->with('success', 'Home page updated successfully.');
    }
    
    public function servicesPage(): View
    {
        $content = SystemSetting::get('cms_services_content', '');
        $metaTitle = SystemSetting::get('cms_services_meta_title', 'Services - ' . config('app.name'));
        $metaDescription = SystemSetting::get('cms_services_meta_description', '');
        
        return view('cms.admin.pages.services', compact('content', 'metaTitle', 'metaDescription'));
    }
    
    public function updateServicesPage(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);
        
        SystemSetting::set('cms_services_content', $validated['content'] ?? '');
        SystemSetting::set('cms_services_meta_title', $validated['meta_title'] ?? '');
        SystemSetting::set('cms_services_meta_description', $validated['meta_description'] ?? '');
        
        return redirect()->route('cms.services')->with('success', 'Services page updated successfully.');
    }
    
    public function doctorsPage(): View
    {
        $content = SystemSetting::get('cms_doctors_content', '');
        $metaTitle = SystemSetting::get('cms_doctors_meta_title', 'Our Doctors - ' . config('app.name'));
        $metaDescription = SystemSetting::get('cms_doctors_meta_description', '');
        
        return view('cms.admin.pages.doctors', compact('content', 'metaTitle', 'metaDescription'));
    }
    
    public function updateDoctorsPage(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);
        
        SystemSetting::set('cms_doctors_content', $validated['content'] ?? '');
        SystemSetting::set('cms_doctors_meta_title', $validated['meta_title'] ?? '');
        SystemSetting::set('cms_doctors_meta_description', $validated['meta_description'] ?? '');
        
        return redirect()->route('cms.doctors-page')->with('success', 'Doctors page updated successfully.');
    }
    
    public function aboutPage(): View
    {
        $content = SystemSetting::get('cms_about_content', '');
        $metaTitle = SystemSetting::get('cms_about_meta_title', 'About Us - ' . config('app.name'));
        $metaDescription = SystemSetting::get('cms_about_meta_description', '');
        
        return view('cms.admin.pages.about', compact('content', 'metaTitle', 'metaDescription'));
    }
    
    public function updateAboutPage(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);
        
        SystemSetting::set('cms_about_content', $validated['content'] ?? '');
        SystemSetting::set('cms_about_meta_title', $validated['meta_title'] ?? '');
        SystemSetting::set('cms_about_meta_description', $validated['meta_description'] ?? '');
        
        return redirect()->route('cms.about')->with('success', 'About page updated successfully.');
    }
    
    public function contactPage(): View
    {
        $content = SystemSetting::get('cms_contact_content', '');
        $metaTitle = SystemSetting::get('cms_contact_meta_title', 'Contact Us - ' . config('app.name'));
        $metaDescription = SystemSetting::get('cms_contact_meta_description', '');
        
        return view('cms.admin.pages.contact', compact('content', 'metaTitle', 'metaDescription'));
    }
    
    public function updateContactPage(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);
        
        SystemSetting::set('cms_contact_content', $validated['content'] ?? '');
        SystemSetting::set('cms_contact_meta_title', $validated['meta_title'] ?? '');
        SystemSetting::set('cms_contact_meta_description', $validated['meta_description'] ?? '');
        
        return redirect()->route('cms.contact-page')->with('success', 'Contact page updated successfully.');
    }
    
    public function featuresPage(): View
    {
        $content = SystemSetting::get('cms_features_content', '');
        $metaTitle = SystemSetting::get('cms_features_meta_title', 'Our Features - ' . config('app.name'));
        $metaDescription = SystemSetting::get('cms_features_meta_description', '');
        
        return view('cms.admin.pages.features', compact('content', 'metaTitle', 'metaDescription'));
    }
    
    public function updateFeaturesPage(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);
        
        SystemSetting::set('cms_features_content', $validated['content'] ?? '');
        SystemSetting::set('cms_features_meta_title', $validated['meta_title'] ?? '');
        SystemSetting::set('cms_features_meta_description', $validated['meta_description'] ?? '');
        
        return redirect()->route('cms.features')->with('success', 'Features page updated successfully.');
    }
    
    public function contactInquiries(): View
    {
        $enquiries = \App\Models\Enquiry::latest()->paginate(20);
        return view('cms.admin.inquiries', compact('enquiries'));
    }
    
    public function seoSettings(): View
    {
        $settings = [
            'site_title' => SystemSetting::get('site_title', config('app.name')),
            'site_description' => SystemSetting::get('site_description', ''),
            'site_keywords' => SystemSetting::get('site_keywords', ''),
            'google_analytics' => SystemSetting::get('google_analytics', ''),
            'meta_tags' => SystemSetting::get('meta_tags', ''),
        ];
        
        return view('cms.admin.seo', compact('settings'));
    }
    
    public function updateSeoSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_title' => 'nullable|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'site_keywords' => 'nullable|string|max:500',
            'google_analytics' => 'nullable|string',
            'meta_tags' => 'nullable|string',
        ]);
        
        foreach ($validated as $key => $value) {
            SystemSetting::set($key, $value);
        }
        
        return redirect()->route('cms.seo')->with('success', 'SEO settings updated successfully.');
    }
}

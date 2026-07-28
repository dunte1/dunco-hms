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
    
    public function headerFooterSettings(): View
    {
        $settings = [
            'header_open_hours' => SystemSetting::get('header_open_hours', 'Mon–Fri 8:00–18:00'),
            'header_emergency_phone' => SystemSetting::get('header_emergency_phone', '+254 700 000 000'),
            'footer_about_text' => SystemSetting::get('footer_about_text', ''),
            'footer_departments' => SystemSetting::get('footer_departments', json_encode([
                ['name' => 'Cardiology', 'link' => '#cardiology'],
                ['name' => 'Radiology', 'link' => '#radiology'],
                ['name' => 'Laboratory', 'link' => '#lab'],
                ['name' => 'Pharmacy', 'link' => '#pharmacy'],
            ])),
            'footer_patient_links' => SystemSetting::get('footer_patient_links', json_encode([
                ['name' => 'Book Appointment', 'link' => '/book-appointment'],
                ['name' => 'Find a Doctor', 'link' => '/doctors'],
                ['name' => 'Contact & Directions', 'link' => '/contact'],
                ['name' => 'Our Features', 'link' => '/features'],
            ])),
            'footer_legal_links' => SystemSetting::get('footer_legal_links', json_encode([
                ['name' => 'Terms of Service', 'link' => '#'],
                ['name' => 'Privacy Policy', 'link' => '#'],
            ])),
            'footer_copyright' => SystemSetting::get('footer_copyright', '© ' . date('Y') . ' ' . config('app.name', 'Dunco Hospital') . '. All rights reserved.'),
            'footer_social_facebook' => SystemSetting::get('footer_social_facebook', ''),
            'footer_social_twitter' => SystemSetting::get('footer_social_twitter', ''),
            'footer_social_instagram' => SystemSetting::get('footer_social_instagram', ''),
            'footer_social_linkedin' => SystemSetting::get('footer_social_linkedin', ''),
            'footer_newsletter_enabled' => SystemSetting::get('footer_newsletter_enabled', '1'),
        ];
        
        return view('cms.admin.header-footer', compact('settings'));
    }
    
    public function updateHeaderFooterSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'header_open_hours' => 'nullable|string|max:255',
            'header_emergency_phone' => 'nullable|string|max:50',
            'footer_about_text' => 'nullable|string',
            'footer_departments' => 'nullable|string',
            'footer_patient_links' => 'nullable|string',
            'footer_legal_links' => 'nullable|string',
            'footer_copyright' => 'nullable|string|max:500',
            'footer_social_facebook' => 'nullable|url',
            'footer_social_twitter' => 'nullable|url',
            'footer_social_instagram' => 'nullable|url',
            'footer_social_linkedin' => 'nullable|url',
            'footer_newsletter_enabled' => 'nullable|boolean',
        ]);
        
        SystemSetting::set('header_open_hours', $validated['header_open_hours'] ?? 'Mon–Fri 8:00–18:00');
        SystemSetting::set('header_emergency_phone', $validated['header_emergency_phone'] ?? '+254 700 000 000');
        SystemSetting::set('footer_about_text', $validated['footer_about_text'] ?? '');
        SystemSetting::set('footer_departments', $validated['footer_departments'] ?? '');
        SystemSetting::set('footer_patient_links', $validated['footer_patient_links'] ?? '');
        SystemSetting::set('footer_legal_links', $validated['footer_legal_links'] ?? '');
        SystemSetting::set('footer_copyright', $validated['footer_copyright'] ?? '');
        SystemSetting::set('footer_social_facebook', $validated['footer_social_facebook'] ?? '');
        SystemSetting::set('footer_social_twitter', $validated['footer_social_twitter'] ?? '');
        SystemSetting::set('footer_social_instagram', $validated['footer_social_instagram'] ?? '');
        SystemSetting::set('footer_social_linkedin', $validated['footer_social_linkedin'] ?? '');
        SystemSetting::set('footer_newsletter_enabled', $validated['footer_newsletter_enabled'] ?? '1');
        
        return redirect()->route('cms.header-footer')->with('success', 'Header & Footer settings updated successfully.');
    }
}

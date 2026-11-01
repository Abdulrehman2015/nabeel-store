<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\DataRequest;
use App\Models\Faq;
use App\Models\Page;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function __construct(protected SeoService $seoService) {}

    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $seo = $this->seoService->resolveMetadata($page);

        return view('storefront.page', compact('page', 'seo'));
    }

    public function contact()
    {
        $seo = $this->seoService->resolveMetadata(null, 'contact', [
            'title' => 'Contact Us & Wholesale Inquiries',
            'description' => 'Get in touch with 99 Bulk Sales customer support and distribution warehouse team.',
        ]);

        return view('storefront.contact', compact('seo'));
    }

    public function submitContact(Request $request)
    {
        // Honeypot check for bots
        if ($request->filled('website')) {
            return redirect()->back()->with('success', 'Message received.');
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:25',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|min:10|max:2000',
        ]);

        ContactMessage::create($request->only(['name', 'email', 'phone', 'subject', 'message']));

        return redirect()->back()->with('success', 'Thank you! Your message has been sent to our customer care team.');
    }

    public function faqs()
    {
        $faqs = Faq::active()->get()->groupBy('category');

        $seo = $this->seoService->resolveMetadata(null, 'faqs', [
            'title' => 'Frequently Asked Questions (FAQs)',
            'description' => 'Answers to questions about bulk delivery, floor rates, minimum order value, and QR payments.',
        ]);

        return view('storefront.faqs', compact('faqs', 'seo'));
    }

    public function dataRequest()
    {
        $seo = $this->seoService->resolveMetadata(null, 'data_request', [
            'title' => 'Customer Data Request (GDPR / PDPA)',
            'robots' => 'noindex, nofollow',
        ]);

        return view('storefront.data_request', compact('seo'));
    }

    public function submitDataRequest(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_email' => 'required|email|max:150',
            'customer_phone' => 'nullable|string|max:25',
            'request_type' => 'required|in:export,delete',
            'notes' => 'nullable|string|max:500',
        ]);

        DataRequest::create([
            'user_id' => auth()->id() ?? null,
            'customer_name' => $request->input('customer_name'),
            'customer_email' => $request->input('customer_email'),
            'customer_phone' => $request->input('customer_phone'),
            'request_type' => $request->input('request_type'),
            'admin_notes' => $request->input('notes'),
        ]);

        return redirect()->back()->with('success', 'Your data request has been logged. Our compliance officer will process your request within 7 business days.');
    }
}


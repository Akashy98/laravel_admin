<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::ordered()->paginate(10);
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'required|string',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Ensure unique slug
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Page::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        Page::create($data);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        return view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'required|string',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Ensure unique slug (excluding current page)
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Page::where('slug', $data['slug'])->where('id', '!=', $page->id)->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        $page->update($data);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted successfully.');
    }

    /**
     * Toggle page status
     */
    public function toggleStatus(Page $page)
    {
        $page->update([
            'status' => $page->status === 'active' ? 'inactive' : 'active'
        ]);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page status updated successfully.');
    }

    /**
     * Create default pages (Terms, Privacy, Refund)
     */
    public function createDefaults()
    {
        $defaultPages = [
            [
                'title' => 'Terms and Conditions',
                'slug' => 'terms-and-conditions',
                'content' => $this->getDefaultTermsContent(),
                'meta_description' => 'Terms and conditions for using our services',
                'meta_keywords' => 'terms, conditions, legal, agreement',
                'status' => 'active',
                'sort_order' => 1
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => $this->getDefaultPrivacyContent(),
                'meta_description' => 'Privacy policy explaining how we handle your data',
                'meta_keywords' => 'privacy, policy, data protection, GDPR',
                'status' => 'active',
                'sort_order' => 2
            ],
            [
                'title' => 'Refund Policy',
                'slug' => 'refund-policy',
                'content' => $this->getDefaultRefundContent(),
                'meta_description' => 'Refund policy for our services',
                'meta_keywords' => 'refund, policy, money back, cancellation',
                'status' => 'active',
                'sort_order' => 3
            ]
        ];

        foreach ($defaultPages as $pageData) {
            if (!Page::where('slug', $pageData['slug'])->exists()) {
                Page::create($pageData);
            }
        }

        return redirect()->route('admin.pages.index')
            ->with('success', 'Default pages created successfully.');
    }

    /**
     * Get default terms and conditions content
     */
    private function getDefaultTermsContent()
    {
        return '<h1>Terms &amp; Conditions</h1>
        <p>These Terms and Conditions, along with the Privacy Policy, govern your use of the AstroIndia website and services. By accessing or using our services, you agree to be bound by these terms. If you do not agree with any part of these terms, please do not use our services.</p>

        <h2>1. Acceptance of Terms</h2>
        <p>By using this website and availing of the Services, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions, as well as our Privacy Policy.</p>

        <h2>2. Use of Services</h2>
        <ul>
        <li>You must be at least 18 years old to use our services.</li>
        <li>You agree to provide accurate and complete information when creating an account or using our services.</li>
        <li>You are responsible for maintaining the confidentiality of your account credentials.</li>
        <li>You agree not to use our services for any unlawful or prohibited purpose.</li>
        </ul>

        <h2>3. Intellectual Property</h2>
        <p>All content, trademarks, logos, and intellectual property on this website are the property of AstroIndia or its licensors. You may not use, reproduce, or distribute any content without our prior written consent.</p>

        <h2>4. Limitation of Liability</h2>
        <p>AstroIndia is not liable for any direct, indirect, incidental, consequential, or punitive damages arising out of your use of our services. We do not guarantee the accuracy, completeness, or reliability of any information provided.</p>

        <h2>5. User Conduct</h2>
        <ul>
        <li>You agree not to engage in any activity that may disrupt or interfere with the functioning of our website or services.</li>
        <li>You agree not to upload, post, or transmit any content that is unlawful, harmful, or violates the rights of others.</li>
        </ul>

        <h2>6. Third-Party Links</h2>
        <p>Our website may contain links to third-party websites. We are not responsible for the content or practices of these external sites.</p>

        <h2>7. Modifications to Terms</h2>
        <p>AstroIndia reserves the right to modify these Terms and Conditions at any time. Changes will be effective upon posting on this page. Your continued use of our services constitutes acceptance of the revised terms.</p>

        <h2>8. Governing Law</h2>
        <p>These terms are governed by the laws of India. Any disputes arising from these terms will be subject to the exclusive jurisdiction of the courts in India.</p>

        <h2>9. Contact Us</h2>
        <p>If you have any questions or concerns about these Terms and Conditions, please contact us at support@astroindia.com.</p>';
    }

    /**
     * Get default privacy policy content
     */
    private function getDefaultPrivacyContent()
    {
        return '<h1>Privacy Policy</h1>
        <p>This Privacy Policy explains how AstroIndia collects, uses, and protects your personal information when you use our website and services. By accessing or using our services, you consent to the practices described in this policy.</p>

        <h2>What Data is Being Collected</h2>
        <ul>
        <li><strong>Personal identification information:</strong> Name, Email address, Phone number, etc.</li>
        <li><strong>Device information:</strong> IP address, browser type, device type, OS, etc.</li>
        <li><strong>Usage data:</strong> Pages visited, time spent, actions taken, etc.</li>
        </ul>

        <h2>What We Do With the Data We Gather</h2>
        <ul>
        <li>Internal record keeping</li>
        <li>Improving our services and user experience</li>
        <li>Sending promotional emails or notifications</li>
        <li>Processing transactions and customer service</li>
        <li>Complying with legal obligations</li>
        </ul>

        <h2>Who We Share Your Data With</h2>
        <ul>
        <li><strong>Service Providers:</strong> We may share information with trusted third-party service providers who assist us in operating our website and services.</li>
        <li><strong>Legal Requirements:</strong> We may disclose your information if required by law or in response to valid requests by public authorities.</li>
        <li><strong>Business Transfers:</strong> In the event of a merger, acquisition, or asset sale, your information may be transferred as part of that transaction.</li>
        </ul>

        <h2>How We Use Cookies</h2>
        <p>We use cookies to collect information and enhance your experience. You can choose to accept or decline cookies through your browser settings.</p>

        <h2>Your Rights &amp; How to Control Data</h2>
        <ul>
        <li><strong>Right to Erasure:</strong> You may request deletion of your data provided there are no legal reasons for us to retain it.</li>
        <li><strong>Withdrawal of Your Consent:</strong> You may withdraw your consent to our use of your data at any time.</li>
        <li><strong>Access &amp; Correction:</strong> You may request access to or correction of your personal information.</li>
        </ul>

        <h2>How Long Will We Store Your Information &amp; How to Contact Us</h2>
        <p>We retain your information only as long as necessary to fulfill the purposes outlined in this policy or as required by law. For any questions, concerns, or to exercise your rights, please contact us at support@astroindia.com.</p>

        <h2>Data Security</h2>
        <p>We use all reasonable methods to protect your data, including encryption and secure storage.</p>

        <h2>Data Protection Officer</h2>
        <p>For any queries, questions, concerns, or grievances about this policy or your data, please contact our Data Protection Officer at support@astroindia.com.</p>';
    }

    /**
     * Get default refund policy content
     */
    private function getDefaultRefundContent()
    {
        return '<h1>Refund Policy</h1>

        <h2>1. Refund Eligibility</h2>
        <p>We offer refunds for our services under specific conditions. Please read this policy carefully to understand when refunds are available.</p>

        <h2>2. Consultation Services</h2>
        <p>For consultation services, refunds may be available if:</p>
        <ul>
            <li>The consultation has not been completed</li>
            <li>There was a technical issue preventing the consultation</li>
            <li>The service was not as described</li>
        </ul>

        <h2>3. Digital Products</h2>
        <p>For digital products and services:</p>
        <ul>
            <li>Refunds are available within 7 days of purchase if the product is defective</li>
            <li>No refunds for downloadable content that has been accessed</li>
            <li>Partial refunds may be considered on a case-by-case basis</li>
        </ul>

        <h2>4. How to Request a Refund</h2>
        <p>To request a refund:</p>
        <ol>
            <li>Contact our customer support team</li>
            <li>Provide your order number and reason for refund</li>
            <li>Allow 3-5 business days for review</li>
        </ol>

        <h2>5. Processing Time</h2>
        <p>Refunds are typically processed within 5-10 business days after approval. The time to appear in your account depends on your payment method and financial institution.</p>

        <h2>6. Non-Refundable Items</h2>
        <p>The following items are non-refundable:</p>
        <ul>
            <li>Completed consultation sessions</li>
            <li>Downloaded digital content</li>
            <li>Gift cards and promotional credits</li>
        </ul>

        <h2>7. Contact Information</h2>
        <p>For refund requests or questions about this policy, please contact us at support@astroindia.com</p>';
    }
}

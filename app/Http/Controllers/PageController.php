<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the specified page by slug
     */
    public function show($slug)
    {
        $page = Page::findBySlug($slug);

        if (!$page) {
            abort(404, 'Page not found');
        }

        return view('pages.show', compact('page'));
    }

    /**
     * Display terms and conditions page
     */
    public function terms()
    {
        $page = Page::findBySlug('terms-and-conditions');

        if (!$page) {
            abort(404, 'Terms and Conditions page not found');
        }

        return view('pages.show', compact('page'));
    }

    /**
     * Display privacy policy page
     */
    public function privacy()
    {
        $page = Page::findBySlug('privacy-policy');

        if (!$page) {
            abort(404, 'Privacy Policy page not found');
        }

        return view('pages.show', compact('page'));
    }

    /**
     * Display refund policy page
     */
    public function refund()
    {
        $page = Page::findBySlug('refund-policy');

        if (!$page) {
            abort(404, 'Refund Policy page not found');
        }

        return view('pages.show', compact('page'));
    }
}

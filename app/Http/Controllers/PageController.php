<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about', [
            'title' => 'About GiftHub',
            'description' => 'Learn about GiftHub and our mission to simplify gift-giving.',
        ]);
    }

    public function howItWorks()
    {
        return view('pages.how-it-works', [
            'title' => 'How GiftHub Works',
            'description' => 'Discover how to create wishlists and organize gift exchanges.',
        ]);
    }

    public function pricing()
    {
        return view('pages.pricing', [
            'title' => 'GiftHub Pricing',
            'description' => 'Choose the perfect plan for your gift-giving needs.',
        ]);
    }

    public function contact()
    {
        return view('pages.contact', [
            'title' => 'Contact Us',
            'description' => 'Get in touch with the GiftHub team.',
        ]);
    }

    public function privacy()
    {
        return view('pages.privacy', [
            'title' => 'Privacy Policy',
            'description' => 'Learn how we protect your data and privacy.',
        ]);
    }

    public function terms()
    {
        return view('pages.terms', [
            'title' => 'Terms of Service',
            'description' => 'Read our terms and conditions for using GiftHub.',
        ]);
    }

    public function cookies()
    {
        return view('pages.cookies', [
            'title' => 'Cookie Policy',
            'description' => 'Understand how we use cookies on GiftHub.',
        ]);
    }
}

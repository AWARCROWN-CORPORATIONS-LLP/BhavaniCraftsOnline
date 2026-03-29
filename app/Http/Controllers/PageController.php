<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function privacy()
    {
        return view('public.pages.privacy');
    }

    public function terms()
    {
        return view('public.pages.terms');
    }

    public function cookie()
    {
        return view('public.pages.cookie');
    }

    public function shipping()
    {
        return view('public.pages.shipping');
    }

    public function faq()
    {
        return view('public.pages.faq');
    }

    public function bundleBuilder()
    {
        $categories = [
            'idols' => \App\Models\Category::where('name', 'LIKE', '%Idols%')->first(),
            'accessories' => \App\Models\Category::where('name', 'LIKE', '%Accessories%')->first(),
            'samagri' => \App\Models\Category::where('name', 'LIKE', '%Samagri%')->first(),
        ];

        $products = [
            'idols' => $categories['idols'] ? $categories['idols']->products()->where('status', 'active')->take(8)->get() : collect(),
            'accessories' => $categories['accessories'] ? $categories['accessories']->products()->where('status', 'active')->take(8)->get() : collect(),
            'samagri' => $categories['samagri'] ? $categories['samagri']->products()->where('status', 'active')->take(12)->get() : collect(),
        ];

        return view('public.bundle_builder', compact('products'));
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [
            // Hero Section
            ['key' => 'hero_badge', 'value' => 'Handcrafted Heritage', 'type' => 'text', 'label' => 'Hero Badge Text', 'section' => 'hero'],
            ['key' => 'hero_title', 'value' => 'Divine Artifacts, <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 to-brand-500">Masterfully</span> Forged.', 'type' => 'textarea', 'label' => 'Hero Title', 'section' => 'hero'],
            ['key' => 'hero_description', 'value' => 'Discover exclusive brass idols, exquisite pooja mandirs, and premium corporate gifts. Each piece is meticulously crafted by generational artisans, bringing eternal grace into your modern sanctuary.', 'type' => 'textarea', 'label' => 'Hero Description', 'section' => 'hero'],
            ['key' => 'hero_button_1_text', 'value' => 'Explore Collection', 'type' => 'text', 'label' => 'Hero Button 1 Text', 'section' => 'hero'],
            ['key' => 'hero_button_1_link', 'value' => '#', 'type' => 'text', 'label' => 'Hero Button 1 Link', 'section' => 'hero'],
            ['key' => 'hero_button_2_text', 'value' => 'B2B Wholesale', 'type' => 'text', 'label' => 'Hero Button 2 Text', 'section' => 'hero'],
            ['key' => 'hero_button_2_link', 'value' => '#', 'type' => 'text', 'label' => 'Hero Button 2 Link', 'section' => 'hero'],
            ['key' => 'hero_bg_image', 'value' => 'https://images.unsplash.com/photo-1600093463592-8e36ae95ef56?q=80&w=2670&auto=format&fit=crop', 'type' => 'image', 'label' => 'Hero Background Image', 'section' => 'hero'],

            // Features Section
            ['key' => 'feature_1_title', 'value' => 'Authentic Craftsmanship', 'type' => 'text', 'label' => 'Feature 1 Title', 'section' => 'features'],
            ['key' => 'feature_1_description', 'value' => 'Every artifact is completely hand-forged by generational artisans using traditional techniques.', 'type' => 'textarea', 'label' => 'Feature 1 Description', 'section' => 'features'],
            
            ['key' => 'feature_2_title', 'value' => 'Global Shipping', 'type' => 'text', 'label' => 'Feature 2 Title', 'section' => 'features'],
            ['key' => 'feature_2_description', 'value' => 'Securely packaged and exported worldwide. We ensure divine artifacts reach your door safely.', 'type' => 'textarea', 'label' => 'Feature 2 Description', 'section' => 'features'],
            
            ['key' => 'feature_3_title', 'value' => 'B2B & Wholesale Dropshipping', 'type' => 'text', 'label' => 'Feature 3 Title', 'section' => 'features'],
            ['key' => 'feature_3_description', 'value' => 'Exclusive partner portals featuring automated restocks and zero-inventory fulfillment systems.', 'type' => 'textarea', 'label' => 'Feature 3 Description', 'section' => 'features'],

            ['key' => 'products_badge', 'value' => 'Featured Artifacts', 'type' => 'text', 'label' => 'Products Section Badge', 'section' => 'products'],
            ['key' => 'products_title', 'value' => 'Curated Masterpieces', 'type' => 'text', 'label' => 'Products Section Title', 'section' => 'products'],

            // Offers Section
            ['key' => 'offer_enabled', 'value' => '0', 'type' => 'text', 'label' => 'Enable Offer Section (1 or 0)', 'section' => 'offers'],
            ['key' => 'offer_title', 'value' => 'Maha Shivaratri Special', 'type' => 'text', 'label' => 'Offer Title', 'section' => 'offers'],
            ['key' => 'offer_description', 'value' => 'Get flat 20% off on all brass idols this festive season. Use code: SHIVA20', 'type' => 'textarea', 'label' => 'Offer Description', 'section' => 'offers'],
            ['key' => 'offer_badge', 'value' => 'Limited Time', 'type' => 'text', 'label' => 'Offer Badge', 'section' => 'offers'],
            ['key' => 'offer_btn_text', 'value' => 'Shop Now', 'type' => 'text', 'label' => 'Offer Button Text', 'section' => 'offers'],
            ['key' => 'offer_bg_image', 'value' => 'https://images.unsplash.com/photo-1619962314121-e4415ccc7f20?q=80&w=2670&auto=format&fit=crop', 'type' => 'image', 'label' => 'Offer Background Image', 'section' => 'offers'],
            ['key' => 'offer_ends_at', 'value' => now()->addDays(7)->toDateTimeString(), 'type' => 'text', 'label' => 'Offer Expiry (YYYY-MM-DD HH:MM:SS)', 'section' => 'offers'],
            ['key' => 'offer_timer_enabled', 'value' => '1', 'type' => 'text', 'label' => 'Enable Countdown Timer (1 or 0)', 'section' => 'offers'],

            // Hero USPs (Feature 2)
            ['key' => 'usp_1_text', 'value' => 'Direct from Generational Artisans', 'type' => 'text', 'label' => 'USP 1 Text', 'section' => 'hero'],
            ['key' => 'usp_2_text', 'value' => 'Insured Global Export', 'type' => 'text', 'label' => 'USP 2 Text', 'section' => 'hero'],
            ['key' => 'usp_3_text', 'value' => 'Sacred Quality Guaranteed', 'type' => 'text', 'label' => 'USP 3 Text', 'section' => 'hero'],

            // Social Proof (Feature 5)
            ['key' => 'social_proof_enabled', 'value' => '1', 'type' => 'text', 'label' => 'Enable Trust Logos (1 or 0)', 'section' => 'social_proof'],
            ['key' => 'trust_logo_1', 'value' => 'https://img.icons8.com/color/96/google-logo.png', 'type' => 'image', 'label' => 'Trust Partner 1', 'section' => 'social_proof'],
            ['key' => 'trust_logo_2', 'value' => 'https://img.icons8.com/color/96/amazon.png', 'type' => 'image', 'label' => 'Trust Partner 2', 'section' => 'social_proof'],
            ['key' => 'trust_logo_3', 'value' => 'https://img.icons8.com/color/96/shopify.png', 'type' => 'image', 'label' => 'Trust Partner 3', 'section' => 'social_proof'],
            ['key' => 'trust_logo_4', 'value' => 'https://img.icons8.com/color/96/facebook-new.png', 'type' => 'image', 'label' => 'Trust Partner 4', 'section' => 'social_proof'],
            ['key' => 'trust_logo_5', 'value' => 'https://img.icons8.com/color/96/instagram-new.png', 'type' => 'image', 'label' => 'Trust Partner 5', 'section' => 'social_proof'],

            // Suggestion Algorithm Settings
            ['key' => 'recommendation_mode', 'value' => 'Festive', 'type' => 'text', 'label' => 'Active Mode (Festive or Heritage)', 'section' => 'suggestions'],
            ['key' => 'recommendation_title', 'value' => 'Sacred Picks for You', 'type' => 'text', 'label' => 'Suggestion Section Title', 'section' => 'suggestions'],
            ['key' => 'recommendation_count', 'value' => '8', 'type' => 'text', 'label' => 'Number of Products to Show', 'section' => 'suggestions'],

            // Gallery Section
            ['key' => 'gallery_title', 'value' => 'Artisanal Highlights', 'type' => 'text', 'label' => 'Gallery Section Title', 'section' => 'gallery'],
            ['key' => 'gallery_image_1', 'value' => 'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?q=80&w=2670&auto=format&fit=crop', 'type' => 'image', 'label' => 'Gallery Image 1', 'section' => 'gallery'],
            ['key' => 'gallery_image_2', 'value' => 'https://images.unsplash.com/photo-1590739225287-bd20498ded45?q=80&w=2670&auto=format&fit=crop', 'type' => 'image', 'label' => 'Gallery Image 2', 'section' => 'gallery'],
            ['key' => 'gallery_image_3', 'value' => 'https://images.unsplash.com/photo-1603412470732-bc66033866b1?q=80&w=2670&auto=format&fit=crop', 'type' => 'image', 'label' => 'Gallery Image 3', 'section' => 'gallery'],
            ['key' => 'gallery_image_4', 'value' => 'https://images.unsplash.com/photo-1600093463592-8e36ae95ef56?q=80&w=2670&auto=format&fit=crop', 'type' => 'image', 'label' => 'Gallery Image 4', 'section' => 'gallery'],
            ['key' => 'gallery_image_5', 'value' => 'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?q=80&w=2670&auto=format&fit=crop', 'type' => 'image', 'label' => 'Gallery Image 5', 'section' => 'gallery'],
            ['key' => 'gallery_image_6', 'value' => 'https://images.unsplash.com/photo-1590739225287-bd20498ded45?q=80&w=2670&auto=format&fit=crop', 'type' => 'image', 'label' => 'Gallery Image 6', 'section' => 'gallery'],
        ];

        foreach ($contents as $content) {
            \App\Models\PageContent::updateOrCreate(['key' => $content['key']], $content);
        }
    }
}

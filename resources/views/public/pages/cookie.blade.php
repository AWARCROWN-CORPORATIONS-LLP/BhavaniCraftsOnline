@extends('public.pages.layout')
@section('page_title', 'Cookie Policy')
@section('page_content')
    <div class="space-y-12">
        <section class="space-y-6">
            <h2 class="text-xl font-black uppercase tracking-[3px] text-onyx-900 border-b-2 border-brand-500 inline-block pb-2 mb-4">1. Cookies & Artifact Tracking</h2>
            <p>Our Website uses “Cookies” to identify the areas of our Website that you have visited. A Cookie is a small piece of data stored on your computer or mobile device by your web browser. We use Cookies to personalize the Content that you see on our Website.</p>
        </section>
        
        <section class="space-y-6">
            <h2 class="text-xl font-black uppercase tracking-[3px] text-onyx-900 border-b-2 border-brand-500 inline-block pb-2 mb-4">2. The Sacred Hierarchy of Cookies</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="border-l-4 border-onyx-900 pl-6 py-2">
                    <h4 class="text-xs font-black uppercase tracking-widest text-onyx-950 mb-2 underline decoration-brand-500 decoration-2">Essential Cookies</h4>
                    <p class="text-[11px] leading-relaxed font-bold">These are crucial for the basic ritual operation of the Website, including cart and secure login features.</p>
                </div>
                <div class="border-l-4 border-onyx-900 pl-6 py-2">
                    <h4 class="text-xs font-black uppercase tracking-widest text-onyx-950 mb-2 underline decoration-brand-500 decoration-2">Analytical Cookies</h4>
                    <p class="text-[11px] leading-relaxed font-bold">We use these to measure how participants interact with our sacred artifacts and which heritage items are most popular.</p>
                </div>
                <div class="border-l-4 border-onyx-900 pl-6 py-2">
                    <h4 class="text-xs font-black uppercase tracking-widest text-onyx-950 mb-2 underline decoration-brand-500 decoration-2">Preference Cookies</h4>
                    <p class="text-[11px] leading-relaxed font-bold">These store your language, currency, and locale preferences for future visits.</p>
                </div>
                <div class="border-l-4 border-onyx-900 pl-6 py-2">
                    <h4 class="text-xs font-black uppercase tracking-widest text-onyx-950 mb-2 underline decoration-brand-500 decoration-2">Marketing Cookies</h4>
                    <p class="text-[11px] leading-relaxed font-bold">These track your visit across other websites for the purpose of serving relevant artifact and artisan advertisements.</p>
                </div>
            </div>
        </section>

        <section class="space-y-6">
            <h2 class="text-xl font-black uppercase tracking-[3px] text-onyx-900 border-b-2 border-brand-500 inline-block pb-2 mb-4">3. External & Third-Party Cookies</h2>
            <p>In addition to our own cookies, we may also use various third-parties cookies to report usage statistics of the Service, deliver advertisements on and through the Service, and so on.</p>
            <ul class="list-disc pl-8 space-y-4 text-xs font-black uppercase tracking-widest text-brand-500">
                <li>Google Analytics (Telemetry Registry)</li>
                <li>Facebook Pixel (Community Tracking)</li>
                <li>Stripe / Razorpay (Payment Gateways)</li>
            </ul>
        </section>

        <section class="space-y-6">
            <h2 class="text-xl font-black uppercase tracking-[3px] text-onyx-900 border-b-2 border-brand-500 inline-block pb-2 mb-4">4. Managing Your Tracker Collection</h2>
            <p>Most browsers allow you to control cookies through their settings, which may be adapted to reflect your consent to the use of cookies. Further, most browsers also enable you to review and erase cookies, including cookies from bhavanicrafts.com.</p>
            <p>Please note that if you delete cookies or refuse to accept them, you might not be able to use all of the features we offer, you may not be able to store your preferences, and some of our pages might not display properly.</p>
        </section>
    </div>
@endsection

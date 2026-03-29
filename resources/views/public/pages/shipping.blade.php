@extends('public.pages.layout')
@section('page_title', 'Shipping & Returns')
@section('page_content')
    <div class="space-y-12">
        <section class="space-y-6">
            <h2 class="text-xl font-black uppercase tracking-[3px] text-onyx-900 border-b-2 border-brand-500 inline-block pb-2 mb-4">1. Dispatching Sacred Artifacts</h2>
            <p>At Bhavani Crafts, every artifact is handled with the utmost respect. We aim to process and ship your sacred items as quickly as possible, ensuring they arrive in pristine condition.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-[11px] font-black uppercase tracking-widest text-[#1e40af] bg-[#1e40af]/5 p-8 rounded-3xl border border-[#1e40af]/10">
                <div class="flex items-center space-x-3"><span class="h-2 w-2 bg-brand-500 rounded-full"></span><span>Processing: 2-3 Business Days</span></div>
                <div class="flex items-center space-x-3"><span class="h-2 w-2 bg-brand-500 rounded-full"></span><span>Transit: 5-7 Business Days</span></div>
                <div class="flex items-center space-x-3"><span class="h-2 w-2 bg-brand-500 rounded-full"></span><span>Custom Items: 15-20 Business Days</span></div>
                <div class="flex items-center space-x-3"><span class="h-2 w-2 bg-brand-500 rounded-full"></span><span>Express: 2-3 Business Days</span></div>
            </div>
            <p>We provide a unique tracking number for every artifact. You will receive this via email once your order has been dispatched.</p>
        </section>
        
        <section class="space-y-6 pt-10">
            <h2 class="text-xl font-black uppercase tracking-[3px] text-onyx-900 border-b-2 border-brand-500 inline-block pb-2 mb-4">2. The Return Ritual</h2>
            <p>If you are not entirely satisfied with your purchase, we're here to help. You have <span class="text-brand-500 font-black">15 calendar days</span> to return an item from the date you received it.</p>
            <ul class="list-disc pl-8 space-y-4 text-xs font-black uppercase tracking-widest text-gray-500">
                <li><span class="text-onyx-900">Original Condition:</span> All items must be unused and in the same state that you received them.</li>
                <li><span class="text-onyx-900">Receipt or Proof:</span> Your item needs to have the receipt or proof of purchase.</li>
                <li><span class="text-onyx-900">Sacred Packaging:</span> All items must be in their original packaging, including protective ritual cloths.</li>
                <li><span class="text-onyx-900">Custom Orders:</span> Please note that customized artifacts are non-returnable.</li>
            </ul>
        </section>

        <section class="space-y-6 pt-10">
            <h2 class="text-xl font-black uppercase tracking-[3px] text-onyx-900 border-b-2 border-brand-500 inline-block pb-2 mb-4">3. Damage & Disruption Registry</h2>
            <p>In the unlikely event that your artifact arrives damaged, please document the damage with photographs and notify our sacred concierge within <span class="text-brand-500 font-black">48 hours</span> of delivery. We will arrange for a replacement or a full refund including shipping costs.</p>
        </section>

        <section class="space-y-6 pt-10">
            <h2 class="text-xl font-black uppercase tracking-[3px] text-onyx-900 border-b-2 border-brand-500 inline-block pb-2 mb-4">4. Restitution & Refunds</h2>
            <p>Once we receive your item, we will inspect it and notify you that we have received your returned item. We will immediately notify you on the status of your refund after inspecting the item. If your return is approved, we will initiate a refund to your credit card (or original method of payment).</p>
            <p>You will receive the credit within a certain amount of days, depending on your card issuer's policies.</p>
        </section>

        <section class="space-y-6 pt-10">
            <h2 class="text-xl font-black uppercase tracking-[3px] text-onyx-900 border-b-2 border-brand-500 inline-block pb-2 mb-4">5. Logistics Investment</h2>
            <p>You will be responsible for paying for your own shipping costs for returning your item. Shipping costs are non-refundable. If you receive a refund, the cost of return shipping will be deducted from your refund.</p>
        </section>
    </div>
@endsection

<?php

namespace App\Http\Controllers;

use App\Models\CorporateRequest;
use Illuminate\Http\Request;

class CorporateRequestController extends Controller
{
    /**
     * Store a newly created corporate request.
     */
    public function store(Request $request, $locale)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'estimated_quantity' => 'nullable|integer',
            'message' => 'nullable|string',
        ]);

        CorporateRequest::create($request->all());

        return back()->with('success', 'Thank you! Your request for the corporate catalog has been sent. Our team will contact you shortly.');
    }

    /**
     * Display a listing of requests for Admin.
     */
    public function adminIndex()
    {
        $requests = CorporateRequest::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.corporate.requests', compact('requests'));
    }

    /**
     * Show details of a request.
     */
    public function adminShow(CorporateRequest $corporateRequest)
    {
        return view('admin.corporate.show', compact('corporateRequest'));
    }

    /**
     * Update status.
     */
    public function updateStatus(Request $request, CorporateRequest $corporateRequest)
    {
        $request->validate([
            'status' => 'required|in:pending,contacted,completed',
        ]);

        $corporateRequest->update(['status' => $request->status]);

        return back()->with('success', 'Request status updated successfully.');
    }
}

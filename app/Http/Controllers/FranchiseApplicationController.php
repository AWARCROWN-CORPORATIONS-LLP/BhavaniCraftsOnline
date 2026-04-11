<?php

namespace App\Http\Controllers;

use App\Models\FranchiseApplication;
use Illuminate\Http\Request;

class FranchiseApplicationController extends Controller
{
    /**
     * Store a newly created franchise application.
     */
    public function store(Request $request, $locale)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'location' => 'required|string|max:255',
            'experience' => 'required|string',
        ]);

        FranchiseApplication::create($request->all());

        return back()->with('success', 'Your application has been received. Our team will contact you soon.');
    }

    /**
     * Display a listing of applications for Admin.
     */
    public function adminIndex()
    {
        $applications = FranchiseApplication::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.franchises.applications', compact('applications'));
    }

    /**
     * Show details of an application.
     */
    public function adminShow(FranchiseApplication $application)
    {
        return view('admin.franchises.application_show', compact('application'));
    }

    /**
     * Update status of an application.
     */
    public function updateStatus(Request $request, FranchiseApplication $application)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,approved,rejected',
            'admin_notes' => 'nullable|string'
        ]);

        $application->update($request->only('status', 'admin_notes'));

        return back()->with('success', 'Application status updated.');
    }
}

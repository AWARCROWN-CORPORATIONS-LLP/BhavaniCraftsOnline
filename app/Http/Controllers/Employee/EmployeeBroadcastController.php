<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\GlobalBroadcast;
use Illuminate\Http\Request;

class EmployeeBroadcastController extends Controller
{
    /**
     * Display a listing of all broadcasts.
     */
    public function index()
    {
        $broadcasts = GlobalBroadcast::orderBy('created_at', 'desc')->paginate(10);
        return view('employee.broadcasts.list', compact('broadcasts'));
    }

    /**
     * Show form for creating a new broadcast.
     */
    public function create()
    {
        return view('employee.broadcasts.create');
    }

    /**
     * Store a newly created broadcast.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,urgent,promo',
            'target_audience' => 'required|in:all,franchise_only,customer_only,exact:employee',
            'expires_at' => 'nullable|date|after:now'
        ]);

        GlobalBroadcast::create(array_merge($request->all(), [
            'is_active' => true,
            'created_by' => auth()->id()
        ]));

        return redirect()->route('employee.broadcasts.index')->with('success', 'Ritual broadcast has been shared with the community.');
    }

    /**
     * Toggle the broadcast activity status.
     */
    public function toggle(GlobalBroadcast $broadcast)
    {
        $broadcast->update(['is_active' => !$broadcast->is_active]);
        return back()->with('success', 'Broadcast status toggled.');
    }
}

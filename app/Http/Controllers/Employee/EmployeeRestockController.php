<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\RestockRequest;
use Illuminate\Http\Request;

class EmployeeRestockController extends Controller
{
    /**
     * Display a listing of restock requests.
     */
    public function index()
    {
        $restocks = RestockRequest::with(['user', 'product'])->orderBy('created_at', 'desc')->paginate(15);
        return view('employee.restocks.list', compact('restocks'));
    }

    /**
     * Update the restock request status.
     */
    public function update(Request $request, RestockRequest $restock)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,fulfilled,cancelled',
            'admin_notes' => 'nullable|string'
        ]);

        $restock->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'responded_at' => now()
        ]);

        return redirect()->route('employee.restocks.index')->with('success', 'Restock status synchronized.');
    }
}

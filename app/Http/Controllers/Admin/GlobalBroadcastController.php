<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\GlobalBroadcast;

class GlobalBroadcastController extends Controller
{
    public function index()
    {
        $broadcasts = GlobalBroadcast::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.broadcasts.list', compact('broadcasts'));
    }

    public function create()
    {
        return view('admin.broadcasts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'urgency' => 'required|in:info,warning,critical',
            'target_audience' => 'required|in:all,exact:employee,exact:franchise',
        ]);

        GlobalBroadcast::create($request->all());

        return redirect()->route('admin.broadcasts.index')->with('success', 'Global Broadcast Transmitted Successfully.');
    }

    public function edit(GlobalBroadcast $broadcast)
    {
        return view('admin.broadcasts.edit', compact('broadcast'));
    }

    public function update(Request $request, GlobalBroadcast $broadcast)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'urgency' => 'required|in:info,warning,critical',
            'target_audience' => 'required|in:all,exact:employee,exact:franchise',
        ]);

        $broadcast->update($request->all());

        return redirect()->route('admin.broadcasts.index')->with('success', 'Broadcast Message Updated.');
    }

    public function toggle(GlobalBroadcast $broadcast)
    {
        $broadcast->is_active = !$broadcast->is_active;
        $broadcast->save();

        return back()->with('success', 'Transmission Status Toggled.');
    }

    public function destroy(GlobalBroadcast $broadcast)
    {
        $broadcast->delete();
        return redirect()->route('admin.broadcasts.index')->with('success', 'Transmission Permanently Deleted.');
    }
}

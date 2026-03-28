<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Illuminate\Http\Request;

class AdminPageContentController extends Controller
{
    public function index($locale)
    {
        $contents = PageContent::all()->groupBy('section');
        return view('admin.page-content.index', compact('contents'));
    }

    public function update(Request $request, $locale)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            // Skip the 'file_' prefixed keys as they are handled via their original keys
            if (str_starts_with($key, 'file_')) continue;

            // Check if there is an uploaded file for this specific key
            $fileKey = 'file_' . $key;
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $fileName = $key . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('page-content', $fileName, 'public');
                $value = '/storage/' . $path;
            }

            // Only update if the value is NOT a temporary blob URL
            // (prevents saving 'blob:http://...' strings to the database)
            if (!str_starts_with($value, 'blob:')) {
                PageContent::where('key', $key)->update(['value' => $value]);
            }
        }

        return redirect()->back()->with('success', 'Page content updated successfully.');
    }
}

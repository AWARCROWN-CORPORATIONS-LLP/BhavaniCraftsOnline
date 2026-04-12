<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Newsletter;
use App\Mail\BulkMarketingMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class BulkEmailController extends Controller
{
    /**
     * Show the bulk email form
     */
    public function index($locale)
    {
        if (!auth()->user()->hasRole('super_admin') && !auth()->user()->hasRole('associate_admin')) {
            abort(403, 'You are not authorized to send bulk emails.');
        }

        $userCount = User::count();
        $subscriberCount = Newsletter::where('status', 'active')->count();

        return view('admin.broadcasts.bulk_email', compact('userCount', 'subscriberCount'));
    }

    /**
     * Process and send the bulk emails
     */
    public function send($locale, Request $request)
    {
        if (!auth()->user()->hasRole('super_admin') && !auth()->user()->hasRole('associate_admin')) {
            abort(403);
        }

        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $subject = $request->subject;
        $content = $request->content;

        // Collect all emails from Users and Newsletters
        $userEmails = User::pluck('email')->toArray();
        $subscriberEmails = Newsletter::where('status', 'active')->pluck('email')->toArray();

        // Merge and unique
        $allEmails = array_unique(array_merge($userEmails, $subscriberEmails));
        $allEmails = array_filter($allEmails, fn($e) => filter_var($e, FILTER_VALIDATE_EMAIL));

        $totalRecipients = count($allEmails);

        // Sending in chunks to avoid timeouts and server load
        // Note: Real production apps should use Queues (Jobs), but we will use a chunk loop here for immediate result.
        
        $successCount = 0;
        $failCount = 0;

        foreach (array_chunk($allEmails, 50) as $chunk) {
            foreach ($chunk as $email) {
                try {
                    Mail::to($email)->send(new BulkMarketingMail($subject, $content));
                    $successCount++;
                } catch (\Exception $e) {
                    Log::error("Bulk Mail Failure to {$email}: " . $e->getMessage());
                    $failCount++;
                }
            }
            // Small sleep to be gentle on mail server if sending many
            if ($totalRecipients > 100) {
                sleep(1);
            }
        }

        return back()->with('success', "Broadcast Complete! Sent to {$successCount} recipients. (Failed: {$failCount})");
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use App\Mail\NewsletterWelcome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a valid email address.'
            ], 422);
        }

        try {
            // Check if already subscribed
            $existing = Newsletter::where('email', $request->email)->first();
            
            if ($existing) {
                if ($existing->status === 'active') {
                    return response()->json([
                        'success' => true,
                        'message' => 'You are already subscribed! Thank you.'
                    ]);
                } else {
                    $existing->status = 'active';
                    $existing->save();
                }
            } else {
                Newsletter::create([
                    'email' => $request->email,
                    'status' => 'active'
                ]);
            }

            // Send Welcome Email
            try {
                Mail::to($request->email)->send(new NewsletterWelcome());
            } catch (\Exception $mailEx) {
                Log::error('Newsletter Welcome Mail Error: ' . $mailEx->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Thank you for subscribing!'
            ]);

        } catch (\Exception $e) {
            Log::error('Newsletter Subscription Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }
}

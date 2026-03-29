<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function ask(Request $request)
    {
        $message = $request->input('message');
        $apiKey = env('GROQ_API_KEY');

        if (!$apiKey) {
            return response()->json(['response' => 'I am having some trouble connecting to my artisan database. Please try again later!']);
        }

        try {
            $response = Http::withToken($apiKey)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "You are the 'Sacred Specialist' at Bhavani Crafts. We sell premium brass idols, pooja items, and handmade artifacts.
                        RULES:
                        1. Use VERY SIMPLE English. No big or complex words.
                        2. Explain things in simple, small steps.
                        3. Be polite and helpful.
                        4. If someone asks about placement, suggest North-East (Ishanya) for idols.
                        5. If someone asks about cleaning, suggest lemon/baking soda or mild soap for brass. 
                        6. Do not mention that you are an AI. Just be the Sacred Specialist."
                    ],
                    ['role' => 'user', 'content' => $message]
                ],
                'temperature' => 0.7,
                'max_tokens' => 500
            ]);

            $result = $response->json();
            $reply = $result['choices'][0]['message']['content'] ?? 'I could not find a perfect answer in our sacred records. Can I help with something else?';

            return response()->json(['response' => $reply]);

        } catch (\Exception $e) {
            return response()->json(['response' => 'I am taking a small ritual break. Please ask again in a moment!']);
        }
    }
}

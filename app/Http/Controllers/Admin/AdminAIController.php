<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminAIController extends Controller
{
    public function generateDescription(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'category' => 'nullable|string',
            'material' => 'nullable|string',
        ]);

        $apiKey = env('GROQ_API_KEY');
        if (!$apiKey) {
            return response()->json(['success' => false, 'error' => 'Groq API Key not configured in .env']);
        }

        $prompt = "Act as a professional product catalog writer. 
        Product: {$request->name}
        Category: {$request->category}
        Material: {$request->material}
        
        Generate:
        1. A simple 2-line Short Description in English (Clear and easy to read).
        2. A helpful Full Description in English (Professional but avoid complex words).
        3. A natural 2-line Short Description in Telugu.
        4. A clear and detailed Full Description in Telugu.
        
        Write for a general customer. Avoid overly poetic, spiritual, or high-level academic words. Use simple business English.
        
        Return the response ONLY as a JSON object with these keys: 
        english_short, english_full, telugu_short, telugu_full.
        Do not include any conversational text or markdown blocks, just the JSON.";


        try {
            $response = Http::withToken($apiKey)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.3-70b-versatile',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a helpful assistant that generates product catalog content in JSON format.'],
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'response_format' => ['type' => 'json_object']
                ]);


            if ($response->successful()) {
                $content = $response->json()['choices'][0]['message']['content'];
                return response()->json([
                    'success' => true,
                    'data' => json_decode($content, true)
                ]);
            }

            return response()->json(['success' => false, 'error' => $response->body()]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}

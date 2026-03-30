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
        $history = $request->input('history', []);
        $apiKey = env('GROQ_API_KEY');

        if (!$apiKey) {
            return response()->json(['response' => 'I am taking a small ritual break. Please ask again in a moment!']);
        }

        // Prepare conversation messages from history
        $formattedMessages = collect($history)->map(function($msg) {
            return [
                'role' => $msg['role'] === 'ai' ? 'assistant' : 'user',
                'content' => $msg['text']
            ];
        })->toArray();

        // System prompt with "Ritual Guide" personality and store knowledge
        $systemPrompt = "You are the 'Sacred Specialist' at Bhavani Crafts. You are a wise and helpful ritual guide.
        We provide premium artifacts for Vedic rituals and home decor.

        GOAL: Your primary task is to guide users in choosing the right items for their rituals (like Griha Pravesh, Wedding, Namakaran, Pooja).
        
        AVAILABLE CATEGORIES AT BHAVANI CRAFTS:
        - Sacred Idols (Brass Idols of Ganesh, Krishna, Lakshmi, etc. for home pooja)
        - Traditional Diyas (Oil lamps, brass lamps, hanging diyas)
        - Pooja Samagri (Essential ritual items like incense, camphor, sacred threads)
        - Home Decor & Ritual Accessories (Brass bells, plate sets, artifacts)
        
        RITUAL GUIDE BEHAVIORS:
        - If the user is unsure what they need, ask about the occasion (e.g., 'Namaste! Are you preparing for a wedding or home blessing?').
        - Once an occasion is identified (e.g., Home Blessing/Griha Pravesh), suggest a 'Sacred Kit'.
        - A 'Sacred Kit' should include 3-4 specific items from our categories (e.g., for Griha Pravesh: a Ganesh Idol, a traditional brass lamp, and an Aarti plate).
        - If the user wants to build their own kit, tell them to visit our Sacred Kit Builder at: /en-in/sacred-kit (or simply suggest they click 'Sacred Kit' in the menu).
        - Use VERY SIMPLE English. Keep explanations gentle and respectful.
        - Suggest placement: North-East (Ishanya) for idols.
        - Cleaning: Use lemon/baking soda or mild soap for brass.
        
        FORMATTING:
        - Be concise. Use bullet points for item lists in a kit.
        - Do not mention you are an AI. You are a Sacred Specialist.";

        $messages = array_merge([['role' => 'system', 'content' => $systemPrompt]], $formattedMessages);

        try {
            $response = Http::withToken($apiKey)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => $messages,
                'temperature' => 0.6,
                'max_tokens' => 800
            ]);

            $result = $response->json();
            $reply = $result['choices'][0]['message']['content'] ?? 'I am taking a moment to reflect. How else can I assist your sacred journey?';

            return response()->json(['response' => $reply]);

        } catch (\Exception $e) {
            return response()->json(['response' => 'The divine connection is weak. Please reach us via WhatsApp or try again later!']);
        }
    }
}

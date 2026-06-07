<?php

namespace App\Http\Controllers;

use App\Ai\Agents\KosBotAgent;
use App\Http\Requests\KosBotChatRequest;
use Illuminate\Http\JsonResponse;
use Laravel\Ai\Enums\Lab;

/**
 * KosBotController
 *
 * Skinny controller — hanya menerima HTTP request, mendelegasikan ke KosBotAgent,
 * dan mengembalikan JSON response ke frontend.
 *
 * Zero business logic di sini.
 */
class KosBotController extends Controller
{
    /**
     * POST /api/bot/chat
     *
     * Menerima pesan dari user dan mengembalikan respons dari KosBotAgent (Gemini).
     * Conversation history dikelola otomatis oleh RemembersConversations trait (server-side).
     * Frontend hanya perlu menyimpan conversation_id di localStorage.
     */
    public function chat(KosBotChatRequest $request): JsonResponse
    {
        try {
            $agent = new KosBotAgent;

            // Untuk guest, kita buat instansiasi User kosong agar RemembersConversations 
            // mengenali ini sebagai "participant" dan menyimpan history-nya dengan user_id = null.
            $user = request()->user() ?? new \App\Models\User();

            // Continue existing conversation or start new one
            if ($conversationId = $request->input('conversation_id')) {
                // Guest users: continue by conversation ID
                $response = $agent
                    ->continue($conversationId, $user)
                    ->prompt($request->input('message'), provider: Lab::Ollama, model: 'llama3.1:latest', timeout: 3000);
            } else {
                // New conversation — require fake user to trigger DB storage
                $agent->forUser($user);
                $response = $agent
                    ->prompt($request->input('message'), provider: Lab::Ollama, model: 'llama3.1:latest', timeout: 3000);
            }

            $extractedResults = [];
            \Log::info('AgentResponse tool calls count: ' . $response->toolResults->count());
            foreach ($response->toolResults as $toolResult) {
                \Log::info('Raw Tool Result: ' . (string) $toolResult->result);
                $data = json_decode((string) $toolResult->result, true);
                if (isset($data['results']) && is_array($data['results'])) {
                    $extractedResults = array_merge($extractedResults, $data['results']);
                }
            }
            \Log::info('Extracted Results Count: ' . count($extractedResults));

            return response()->json([
                'success'         => true,
                'reply'           => (string) $response,
                'conversation_id' => $response->conversationId,
                'results'         => $extractedResults,
            ]);
        } catch (\Throwable $e) {
            // Log error untuk debugging tapi jangan expose stack trace ke user
            logger()->error('KosBot error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'reply'   => 'Maaf, KosBot sedang mengalami gangguan. Silakan coba lagi dalam beberapa saat. 🙏',
                'conversation_id' => $request->input('conversation_id'),
            ], 503);
        }
    }
}

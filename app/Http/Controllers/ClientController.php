<?php

namespace App\Http\Controllers;

use App\Models\Training\TrainingSessionInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected $session;

    public function __construct(TrainingSessionInfo $trainingSessionInfo)
    {
        $this->session = $trainingSessionInfo;
    }

    /**
     * Method to get sessions of authenticated client
     *
     * @return JsonResponse
     */
    public function sessions(): JsonResponse
    {
        $client = auth('clients')->user();
        if (!$client) return response()->json([], 401);
        return $this->getTrainingSessionsById(auth('clients')->id());
    }

    /**
     * Method to get sessions by client
     *
     * @param int $clientId
     *
     * @return JsonResponse
     */
    public function getTrainingSessionsById(int $clientId): JsonResponse
    {
        $t = $this->session::query()
            ->byClient($clientId)
            ->get();

        return response()->json(['sessions' => $t], 200);
    }

    /**
     * Method to cancel the session on client side
     *
     * @param int $sessionId
     * @return JsonResponse
     */
    public function cancelSession(int $sessionId): JsonResponse
    {
        $user = auth('clients')->user();
        if (!$user) return response()->json([], 401);

        $session = $user->sessions()->where('training_session_id', $sessionId);
        $session->delete();

        return response()->json([
            'status' => sizeof($session) ? 'Deleted' : 'Not found',
            'count' => sizeof($session)
        ], sizeof($session) ? 200 : 404);
    }

    /**
     * Method to book a session on client side
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bookSession(Request $request): JsonResponse
    {
        $user = auth('clients')->user();
        if (!$user) return response()->json([], 401);
        if (!$user->isAvailable()) return response()->json(['status' => 'error', 'description' => 'User is not available']);

        return response()->json([], 200);
    }
}

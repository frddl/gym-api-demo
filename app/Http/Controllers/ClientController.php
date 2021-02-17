<?php

namespace App\Http\Controllers;

use App\Models\Training\TrainingSessionInfo;
use Illuminate\Http\JsonResponse;

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

    public function cancelSession(int $sessionId): JsonResponse {
        $user = auth('trainers')->user();
        if (!$user) return response()->json([], 401);

        $session = $user->sessions()->where('training_session_id', $sessionId);
        $session->delete();

        return response()->json([
            'status' => sizeof($session) ? 'Deleted' : 'Not found',
            'count' => sizeof($session)
        ], sizeof($session) ? 200 : 404);
    }
}

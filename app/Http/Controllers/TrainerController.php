<?php

namespace App\Http\Controllers;

use App\Models\Training\TrainingSession;
use App\Models\Users\Trainer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class TrainerController extends Controller
{
    protected $trainer;
    protected $appointment;

    public function __construct(Trainer $trainer, TrainingSession $session)
    {
        $this->trainer = $trainer;
        $this->appointment = $session;
    }

    /**
     * Method to get list of all trainers
     *
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        $trainers = $this->trainer::all();
        return response()->json($trainers, 200);
    }

    /**
     * Method to get time information on trainer schedule
     *
     * @param int $trainerId
     *
     * @return JsonResponse
     */
    public function sessions(int $trainerId): JsonResponse
    {
        $trainer = $this->trainer::findOrFail($trainerId);
        $sessions = $trainer->sessionsForWeek()->get();

        $result = [];
        $date = Carbon::now();

        for ($i = 0; $i < 7; $i++) {
            $sch = $this->getSchedule($trainer, $sessions, $date->format('Y-m-d'));
            if (count($sch['sessions'])) array_push($result, $sch);
            $date->add('days', 1);
        }

        return response()->json($result, 200);
    }

    /**
     * Method to get information about trainer schedule by date
     *
     * @param Trainer $trainer
     * @param Collection $sessions
     * @param string $date
     *
     * @return array
     */
    protected function getSchedule(Trainer $trainer, Collection $sessions, string $date): array
    {
        $schedule = TrainingSession::byDate($date)->byTrainer($trainer->id)->get();

        return [
            'date' => $date,
            'sessions' => $schedule,
        ];
    }

    /**
     * Method to get only free training session slots
     *
     * @param int $trainerId
     * @return JsonResponse
     */
    public function free(int $trainerId): JsonResponse
    {
        $sessions = TrainingSession::byTrainer($trainerId)->without('trainer')->get();
        $result = [];

        foreach ($sessions as $s) {
            if ($s->is_free) array_push($result, $s);
        }

        return response()->json($result, 200);
    }
}

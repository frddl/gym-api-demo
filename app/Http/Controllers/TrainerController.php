<?php

namespace App\Http\Controllers;

use App\Helpers\DateTimeFormatValidator;
use App\Models\Training\TrainingSession;
use App\Models\Users\Trainer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     * Method to get information about the training session with the clients
     *
     * @param int $sessionId
     * @return JsonResponse
     */
    public function session(int $sessionId): JsonResponse
    {
        $trainer = auth('trainers')->user();
        if (!$trainer) return response()->json([], 401);
        $session = $trainer->sessions()->without('trainer')->where('id', $sessionId)->first();
        $session['clients'] = $session->clients;
        return response()->json($session, 200);
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

    /**
     * @return JsonResponse
     */
    public function mySessions(): JsonResponse
    {
        $user = auth('trainers')->user();
        if (!$user) return response()->json([], 401);
        $sessions = $user->sessions()->without('trainer')->get();

        return response()->json($sessions, 200);
    }

    /**
     * @param int $sessionId
     * @return JsonResponse
     */
    public function cancelSession(int $sessionId): JsonResponse
    {
        $user = auth('trainers')->user();
        if (!$user) return response()->json([], 401);

        $session = $user->sessions()->without('trainer')->where('id', $sessionId)->first();
        // TODO: checking for start time
        $session->delete();

        return response()->json([
            'status' => sizeof($session) ? 'Deleted' : 'Not found',
            'count' => sizeof($session)
        ], sizeof($session) ? 200 : 404);
    }

    /**
     * Method for trainers to create sessions
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createSession(Request $request): JsonResponse
    {
        $user = auth('trainers')->user();
        if (!$user) return response()->json([], 401);

        $date = $request->input('date');
        $start = $request->input('start_time');
        $end = $request->input('end_time');

        if (!DateTimeFormatValidator::dateIsValid($date) ||
            !DateTimeFormatValidator::timeDurationIsValid($start, $end)) {
            return response()->json([
                'status' => 'error',
                'description' => 'Time or date has an invalid format',
            ], 403);
        }

        $shift_start = config()->get('training-shift.start_time');
        $shift_end = config()->get('training-shift.end_time');
        if ($this->hhToMinutes($start) < $this->hhToMinutes($shift_start) || $this->hhToMinutes($start) > $this->hhToMinutes($shift_end) || $this->hhToMinutes($end) > $this->hhToMinutes($shift_end) || $this->hhToMinutes($end) < $this->hhToMinutes($shift_start)) {
            return response()->json([
                'status' => 'error',
                'description' => 'The gym does not operate during given hours',
            ], 403);
        }

        // checking if there is a session with this information already
        $checkSession = TrainingSession::byDate($date)->get();
        $filtered = $checkSession->filter(function ($model) use ($start, $end) {
            $diff_before_start = ($this->hhToMinutes($model->start_time) > $this->hhToMinutes($start) && $this->hhToMinutes($model->start_time) > $this->hhToMinutes($end));
            $diff_after_end = ($this->hhToMinutes($model->end_time) < $this->hhToMinutes($end) && $this->hhToMinutes($model->end_time) < $this->hhToMinutes($start));

            return !($diff_after_end || $diff_before_start);
        });

        if ($filtered->count()) return response()->json([
            'status' => 'error',
            'description' => 'There is already a training session for given time period',
        ], 403);

        $session = TrainingSession::create([
            'date' => $date,
            'start_time' => $start,
            'end_time' => $end,
            'trainer_id' => $user->id,
        ]);

        return response()->json($session, 200);
    }

    /**
     * As i messed up the way time is stored in db, here is a shameful method for working with time. :(
     *
     * @param $hh
     * @return int
     */
    protected function hhToMinutes($hh): int
    {
        $parts = explode(":", $hh);
        return ($parts[0] * 60) + $parts[1];
    }
}

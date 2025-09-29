<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Event::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('banners', 'public');
            $validated['banner'] = $path;
        }

        $validated['status'] = 'scheduled';

        $event = Event::create($validated);

        return response()->json($event, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return $event->load('organization');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $validated = $request->validated();

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('banners', 'public');
            $validated['banner'] = $path;
        }

        $event->update($validated);

        return response()->json($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json(null, 204);
    }

    public function getStatisticsForEvent(Event $event)
    {
        $capacidadMaxima = $event->capacity;

        $capacidadActual = DB::table('access_logs as a')
            ->select('a.participant_id', 'a.person_type', DB::raw('MAX(a.created_at) as last_action_time'))
            ->join(
                DB::raw('(SELECT participant_id, MAX(created_at) as max_time 
                     FROM access_logs 
                     GROUP BY participant_id) b'),
                function ($join) {
                    $join->on('a.participant_id', '=', 'b.participant_id')
                        ->on('a.created_at', '=', 'b.max_time');
                }
            )
            ->where('a.action', 'ENTRY')
            ->count();

        $counts = Participant::where('event_id', $event->id)
            ->whereNotNull('approved_by')
            ->leftJoin('invitees', 'participants.id', '=', 'invitees.participant_id')
            ->selectRaw('COUNT(DISTINCT participants.id) as participants_count, COUNT(invitees.id) as invitees_count')
            ->first();

        $participantsCount = $counts->participants_count;
        $inviteesCount = $counts->invitees_count;

        $teamEntries = DB::table('access_logs as a')
            ->join('participants as p', 'a.participant_id', '=', 'p.id')
            ->join('teams as t', 'p.team_id', '=', 't.id')
            ->select('t.id as team_id', 't.name as team_name', DB::raw('COUNT(*) as entry_total'))
            ->join(
                DB::raw('(SELECT participant_id, MAX(created_at) as max_time 
                 FROM access_logs 
                 GROUP BY participant_id) b'),
                function ($join) {
                    $join->on('a.participant_id', '=', 'b.participant_id')
                        ->on('a.created_at', '=', 'b.max_time');
                }
            )
            ->where('a.action', 'ENTRY')
            ->groupBy('t.id', 't.name')
            ->get();

        $teamParticipants = Participant::select('team_id', DB::raw('COUNT(*) as participants'))
            ->where('event_id', $event->id)
            ->whereNotNull('approved_by')
            ->groupBy('team_id')
            ->get();

        $controlAforo = Team::whereHas('participants', function ($q) use ($event) {
            $q->where('event_id', $event->id);
        })
            ->get()
            ->map(function ($team) use ($teamParticipants, $teamEntries) {
                $participantsCount = $teamParticipants->firstWhere('team_id', $team->id)->participants ?? 0;
                $entryCount = $teamEntries->firstWhere('team_id', $team->id)->entry_total ?? 0;

                return [
                    'team_name'   => $team->name,
                    'participants' => $participantsCount,
                    'entry_total' => $entryCount,
                ];
            });

        return response()->json([
            'registro_general' => $participantsCount + $inviteesCount,
            'aforo_maximo' => $capacidadMaxima,
            'aforo_actual' => $capacidadActual,
            'control_aforo' => $controlAforo,
            'condicion' => $event->status
        ]);
    }

    public function updateStatus(Request $request, Event $event)
    {
        $validated = $request->validate([
            'status' => 'required|in:PENDING,REGISTERING,ACCESSING,CLOSED,FINISHED',
        ]);

        $event->status = $validated['status'];
        $event->save();

        return response()->json([
            'message' => 'Estado del evento actualizado',
            'condicion' => $event->status,
        ]);
    }
}

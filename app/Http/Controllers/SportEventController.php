<?php

namespace App\Http\Controllers;

use App\Models\SportEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SportEventController extends Controller
{
    // Get a list of all sport events
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);

        $sportEvents = SportEvent::paginate($perPage);

        return response()->json($sportEvents, 200);
    }

    // Create a new sport event
    public function store(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'eventDate' => 'required|date',
            'eventName' => 'required|string|max:255',
            'eventType' => 'required|string|max:255',
            'organizer_id' => 'required|exists:organizers,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create and save the sport event
        $sportEvent = SportEvent::create($request->all());

        return response()->json($sportEvent, 201);
    }

    // Get a single sport event by ID
    public function show($id)
    {
        $sportEvent = SportEvent::find($id);

        if (!$sportEvent) {
            return response()->json(['message' => 'Sport event not found'], 404);
        }

        return response()->json($sportEvent, 200);
    }

    // Update a sport event by ID
    public function update(Request $request, $id)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'eventDate' => 'date',
            'eventName' => 'string|max:255',
            'eventType' => 'string|max:255',
            'organizer_id' => 'exists:organizers,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $sportEvent = SportEvent::find($id);

        if (!$sportEvent) {
            return response()->json(['message' => 'Sport event not found'], 404);
        }

        // Update sport event data
        $sportEvent->update($request->all());

        return response()->json($sportEvent, 200);
    }

    // Delete a sport event by ID
    public function destroy($id)
    {
        $sportEvent = SportEvent::find($id);

        if (!$sportEvent) {
            return response()->json(['message' => 'Sport event not found'], 404);
        }

        $sportEvent->delete();

        return response()->json(['message' => 'Sport event deleted'], 204);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Organizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrganizerController extends Controller
{
    // Get a list of all organizers
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);

        $organizers = Organizer::paginate($perPage);

        return response()->json($organizers, 200);
    }

    // Create a new organizer
    public function store(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'organizerName' => 'required|string|max:255',
            'imageLocation' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create and save the organizer
        $organizer = Organizer::create($request->all());

        return response()->json($organizer, 201);
    }

    // Get a single organizer by ID
    public function show($id)
    {
        $organizer = Organizer::find($id);

        if (!$organizer) {
            return response()->json(['message' => 'Organizer not found'], 404);
        }

        return response()->json($organizer, 200);
    }

    // Update an organizer by ID
    public function update(Request $request, $id)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'organizerName' => 'string|max:255',
            'imageLocation' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $organizer = Organizer::find($id);

        if (!$organizer) {
            return response()->json(['message' => 'Organizer not found'], 404);
        }

        // Update organizer data
        $organizer->update($request->all());

        return response()->json($organizer, 200);
    }

    // Delete an organizer by ID
    public function destroy($id)
    {
        $organizer = Organizer::find($id);

        if (!$organizer) {
            return response()->json(['message' => 'Organizer not found'], 404);
        }

        $organizer->delete();

        return response()->json(['message' => 'Organizer deleted'], 204);
    }
}

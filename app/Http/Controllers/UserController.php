<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Create a new user
    public function store(Request $request)
    {
        try {
            Log::info("Creating New User");
            
            // Validate request data
            $validatedData = $request->validate([
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->ignore($request->id),
                ],
                'password' => 'required|string|min:6|confirmed',
            ]);
    
            // Create and save the user
            $user = User::create([
                'firstName' => $validatedData['firstName'],
                'lastName' => $validatedData['lastName'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);
    
            // Return the newly created user
            return response()->json($user, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Validation error while creating user: " . $e->getMessage());
            return response()->json(['message' => 'Email address is already registered.'], 422);
        } catch (\Exception $e) {
            Log::error("Error creating user: " . $e->getMessage());
            return response()->json(['message' => 'An error occurred while creating the user'], 500);
        }
    }
    

    // Retrieve a user by ID
    public function show($id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            return response()->json($user);
        } catch (\Exception $e) {
            Log::error("Error retrieving user: " . $e->getMessage());
            return response()->json(['message' => 'An error occurred while retrieving the user'], 500);
        }
    }

    // Update a user by ID
    public function update(Request $request, $id)
    {
        try {
            // Validate request data
            $validatedData = $request->validate([
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                // Add more validation rules as needed
            ]);

            $user = User::find($id);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Update user data
            $user->update($validatedData);

            return response()->json($user, 200);
        } catch (\Exception $e) {
            Log::error("Error updating user: " . $e->getMessage());
            return response()->json(['message' => 'An error occurred while updating the user'], 500);
        }
    }

    // Delete a user by ID
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $user->delete();

            return response()->json(['message' => 'User deleted'], 204);
        } catch (\Exception $e) {
            Log::error("Error deleting user: " . $e->getMessage());
            return response()->json(['message' => 'An error occurred while deleting the user'], 500);
        }
    }

    // Change user password
    public function changePassword(Request $request, $id)
    {
        try {
            // Validate request data
            $validatedData = $request->validate([
                'oldPassword' => 'required|string',
                'newPassword' => 'required|string|min:6|confirmed',
            ]);

            $user = User::find($id);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Check if the old password matches the user's current password
            if (!Hash::check($validatedData['oldPassword'], $user->password)) {
                throw ValidationException::withMessages(['oldPassword' => 'The provided password is incorrect']);
            }

            // Update the user's password
            $user->update([
                'password' => Hash::make($validatedData['newPassword']),
            ]);

            return response()->json(['message' => 'Password changed successfully'], 204);
        } catch (\Exception $e) {
            Log::error("Error changing password: " . $e->getMessage());
            return response()->json(['message' => 'An error occurred while changing the password'], 500);
        }
    }

    // User login
    public function loginUser(Request $request)
    {
        try {
            // Validate request data
            $validatedData = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            // Attempt to authenticate the user
            if (Auth::attempt($validatedData)) {
                $user = Auth::user();
                $token = $user->createToken('authToken')->accessToken;

                return response()->json([
                    'id' => $user->id,
                    'email' => $user->email,
                    'token' => $token,
                ], 200);
            } else {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
        } catch (\Exception $e) {
            Log::error("Error logging in user: " . $e->getMessage());
            return response()->json(['message' => 'An error occurred while logging in'], 500);
        }
    }
}

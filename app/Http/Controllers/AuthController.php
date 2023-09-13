<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $response = Http::post(env('SPORT_EVENTS_API_URL') . '/users', [
            'firstName' => $request->input('firstName'),
            'lastName' => $request->input('lastName'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'repeatPassword' => $request->input('repeatPassword'),
        ]);

        return $response->json();
    }

    public function login(Request $request)
    {
        $response = Http::post(env('SPORT_EVENTS_API_URL') . '/users/login', [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        return $response->json();
    }
}

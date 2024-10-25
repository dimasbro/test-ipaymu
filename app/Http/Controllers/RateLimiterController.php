<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RateLimiterController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(['success' => 'Request processed'], 200);
    }
}

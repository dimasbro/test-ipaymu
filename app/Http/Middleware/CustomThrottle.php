<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class CustomThrottle
{
    public function handle($request, Closure $next, $maxAttempts = 100, $decayMinutes = 60)
    {
        $key = $this->resolveRequestSignature($request);
        $currentAttempts = Cache::get($key, 0);

        if ($currentAttempts >= $maxAttempts) {
            return response()->json(['error' => 'Too Many Requests'], 429);
        }

        Cache::put($key, $currentAttempts + 1, $decayMinutes * 60);

        return $next($request);
    }

    protected function resolveRequestSignature($request)
    {
        return 'throttle:' . $request->ip();
    }
}

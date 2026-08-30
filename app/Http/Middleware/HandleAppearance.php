<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class HandleAppearance
{
    /**
     * The appearances the client is allowed to ask for.
     *
     * @var list<string>
     */
    private const APPEARANCES = ['light', 'dark', 'system'];

    /**
     * Read the appearance the request asks for, falling back to "system".
     *
     * The cookie is written client-side and left unencrypted, so its value
     * cannot be trusted.
     */
    public static function resolve(Request $request): string
    {
        $appearance = $request->cookie('appearance');

        return in_array($appearance, self::APPEARANCES, true)
            ? $appearance
            : 'system';
    }

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        View::share('appearance', self::resolve($request));

        return $next($request);
    }
}

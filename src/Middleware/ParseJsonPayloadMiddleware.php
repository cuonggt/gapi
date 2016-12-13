<?php

namespace Gtk\Gapi\Middleware;

use Closure;

class ParseJsonPayloadMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->isJson()) {
            $data = $request->json()->all();

            $request->request->replace(is_array($data) ? $data : []);
        }

        return $next($request);
    }
}
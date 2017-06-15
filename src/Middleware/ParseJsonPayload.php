<?php

namespace Gtk\Gapi\Middleware;

use Closure;

class ParseJsonPayload
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
        if ($this->shouldParseJsonPayload($request)) {
            $data = $request->json()->all();

            $request->request->replace(is_array($data) ? $data : []);
        }

        return $next($request);
    }

    /**
     * Determine if should parse JSON payload.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function shouldParseJsonPayload($request)
    {
        return in_array($request->method(), ['POST', 'PUT', 'PATCH']) && $request->isJson();
    }
}

<?php namespace MyAccount\Http\Middleware;

use Closure;

class HttpsProtocol {

    public function handle($request, Closure $next)
    {

    	//$request->setTrustedProxies( [ $request->getClientIp() ] );
            if (!$request->secure() && env('APP_ENV') === 'prod') {

                return redirect()->secure($request->path());
                //getRequestUri()
            }

            return $next($request); 
    }
}

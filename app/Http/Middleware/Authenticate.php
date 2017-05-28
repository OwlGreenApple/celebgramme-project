<?php

namespace Celebgramme\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

use Celebgramme\Models\User;
use Carbon;

class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                // return redirect()->guest('/');
            }
        }
				
				$dt = Carbon::now();
				$user = Auth::user();
        if (!is_null($user)) {
          $user->last_seen = $dt->toDateTimeString();
          $user->save();
        }
        
        return $next($request);
    }
}

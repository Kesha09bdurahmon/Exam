<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class User extends Controller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $check_AppUser = AppUser::where("token",$this->getToken())->first();
        if($check_AppUser == null){
            return $this->sendResponse(null,false,"User topilmadi");
        }
        $request->check_AppUser = $check_AppUser;
        return $next($request);
    }
}

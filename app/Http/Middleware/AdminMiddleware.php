<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (array_key_exists('user_server_token', $_COOKIE)) {

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, config('app.auth_laravel_url') . '/api/check-token');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $_COOKIE['user_server_token']]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, []);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            $response = json_decode($server_output);
            if ($httpCode === 200 && $response->success) {
                if (Auth::check()) {
                    if ($response->data && in_array($response->data->role , ['admin' , 'contenter', 'writer'])) {
                        return $next($request);
                    } else {
                        Auth::logout();
                        return redirect()->away(config('app.login_url'));
                    }
                } else {
                    if ($response->data && in_array($response->data->role , ['admin' , 'contenter', 'writer']) ) {
                        $user = User::where('username', $response->data->username)->first();
                        if ($user) {
                            Auth::loginUsingId($user->id);
                        } else {
                            $user = new User();
                            $user->username = $response->data->username;
                            $user->mobile = $response->data->mobile;
                            $user->email = $response->data->email;
                            $user->role = $response->data->role;
                            $user->timestamp = time();
                            $user->name = $response->data->name;
                            $user->ex_user_id = $response->data->id;

                            $user->save();
                            Auth::loginUsingId($user);

                        }
                        return $next($request);
                    } else {
                        return redirect()->away(config('app.shop_url'));
                    }
                }
            } else {
                setcookie("user_server_token", "", time() - 3600, '/', config('app.cookie_url'));
                if (isset($_COOKIE['class_token'])) {
                    setcookie("class_token", "", time() - 3600, '/', config('app.cookie_url'));
                }
                return redirect()->away(config('app.login_url'));
            }
        } else {
            if (Auth::check()) {
                Auth::logout();
            }
            return redirect()->away(config('app.login_url'));
        }


    }
}

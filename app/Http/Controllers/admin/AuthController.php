<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Passport\TokenRepository;

class AuthController extends Controller
{
    public function logout(Request $request){
        $user = $request->user();

        if (array_key_exists('class_token', $_COOKIE)){
            $classToken = explode('.' , $_COOKIE['class_token']);
            if (count($classToken) ===3){
                if(json_decode(base64_decode($classToken[1]))->exp > time()){
                    $tokenRepository = app(TokenRepository::class);
                    $tokenRepository->revokeAccessToken(json_decode(base64_decode($classToken[1]))->jti);
                    setcookie("class_token", "", time() - 3600, '/', config('app.cookie_url'));

                }
            }
        }



        if (array_key_exists('user_server_token', $_COOKIE)){
            $cURLConnection = curl_init(config('app.auth_laravel_url').'/api/logout');
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURLConnection, CURLINFO_HEADER_OUT, true);
            curl_setopt($cURLConnection, CURLOPT_POST, true);
            curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer '.$request->get('serverToken'),
            ));
            curl_exec($cURLConnection);
            curl_close($cURLConnection);
            setcookie("user_server_token", "", time() - 3600, '/', config('app.cookie_url'));

        }


        return redirect()->away(config('app.shop_url'));

    }
}

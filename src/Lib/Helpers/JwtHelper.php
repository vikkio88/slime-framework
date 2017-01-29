<?php
namespace App\Lib\Helpers;

use Firebase\JWT\JWT;

class JwtHelper
{

    public static function encode($payload = [])
    {
        $key = Config::get('app.key');
        return JWT::encode($payload, $key);
    }

    public static function decode($token)
    {
        $key = Config::get('app.key');
        return (array)JWT::decode($token, $key, ['HS256']);
    }
}
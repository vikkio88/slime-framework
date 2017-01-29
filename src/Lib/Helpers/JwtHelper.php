<?php
namespace App\Lib\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;

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
        try {
            return (array)JWT::decode($token, $key, ['HS256']);
        } catch (SignatureInvalidException $e) {
            return null;
        }
    }
}
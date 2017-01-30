<?php


namespace App\Lib\Helpers;

class TokenHelper
{
    public static function getTokenPayload($userId)
    {
        $timestamp = time();
        return [
            'userId' => $userId,
            'validUntil' => $timestamp + (Config::get('app.tokenLife') * 3600),
            'createdAt' => $timestamp
        ];
    }

    public static function generateRandomToken()
    {
        return JwtHelper::encode([str_random()]);
    }
}
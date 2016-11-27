<?php


namespace App\Lib\Helpers;


class Validation
{
    public static function validate()
    {
        return new self();
    }

    public function isValid()
    {
        return true;
    }

    public function errors()
    {
        return [];
    }
}
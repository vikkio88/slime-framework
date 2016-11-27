<?php


namespace Tests\Helpers\Stubs;


use App\Lib\Slime\Models\CrudModel;

class CrudExample extends CrudModel
{
    protected $fillable = [
        'banana',
        'stuff1'
    ];

    protected $rules = [
        'banana' => 'required'
    ];

    //overwriting the create method to prevent Db Interaction
    public static function create(array $attributes = [])
    {
        return $attributes;
    }
}
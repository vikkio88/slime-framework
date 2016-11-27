<?php


namespace App\Lib\Helpers;


use App\Lib\Slime\Exceptions\Http\UnprocessableEntityException;
use App\Lib\Slime\Models\CrudModel;

class Validator
{

    private static function validate(array $requestBody, CrudModel $modelClass, $forcedField = [])
    {
        $fields = self::extractFromRequest($requestBody, $modelClass, $forcedField);
        $fields = array_merge($fields, $forcedField);
        $validation = $modelClass->validate($fields);
        if (!$validation->isValid()) {
            throw new UnprocessableEntityException($validation->errors());
        }

        return $fields;
    }

    public static function validateAndCreate(array $requestBody, CrudModel $modelClass, $forcedField = [])
    {
        $fields = self::validate($requestBody, $modelClass, $forcedField);
        return $modelClass::create($fields);
    }

    public static function validateAndUpdate(array $requestBody, CrudModel $modelClass, $forcedField = [])
    {
        $fields = self::validate($requestBody, $modelClass, $forcedField);
        if ($modelClass->update($fields)) {
            return $modelClass;
        }
    }

    private static function extractFromRequest(array $requestBody, CrudModel $modelClass, array $forcedField)
    {
        $allowedValues = array_values(
            array_diff(
                $modelClass->getFillable(),
                array_keys($forcedField)
            )
        );
        return array_intersect_key($requestBody, array_flip($allowedValues));
    }
}
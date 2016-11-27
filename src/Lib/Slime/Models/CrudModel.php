<?php


namespace App\Lib\Slime\Models;
use App\Lib\Helpers\Validation;


/**
 * Class CrudModel
 * @package App\Lib\Slime\Models
 */
abstract class CrudModel extends SlimeModel
{
    /**
     * @var array
     */
    protected $rules = [];
    private $validator;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        //$this->validator = new Validator();
    }


    /**
     * @return array
     */
    protected function rules()
    {
        $rules = $this->rules;
        if (!empty($this->id)) { //if we are on updating process
            foreach ($rules as $field => $rule) {
                if (strpos($rule, 'unique') !== false) {
                    $rules[$field] = $rule . "," . $this->id; //exclude that rule from unique validation
                }
            }
        }
        return $rules;
    }

    public function validate(array $data)
    {
        return Validation::validate($this->rules(), $data);
    }

}
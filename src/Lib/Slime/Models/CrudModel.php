<?php


namespace App\Lib\Slime\Models;

use Sirius\Validation\Validator;

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
    private $validator = null;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }


    /**
     * @return array
     */
    protected function rules()
    {
        return $this->rules;
        /*
        $rules = $this->rules;
        if (!empty($this->id)) { //if we are on updating process
            foreach ($rules as $field => $rule) {
                if (strpos($rule, 'unique') !== false) {
                    $rules[$field] = $rule . "," . $this->id; //exclude that rule from unique validation
                }
            }
        }
        return $rules;
        */
    }

    public function validate(array $data)
    {
        if ($this->validator === null) {
            $this->validator = new Validator();
            $this->validator->add($this->rules());
        }
        $this->validator->validate($data);
        return $this->validator;
    }

}
<?php


use App\Lib\Helpers\Validator;
use Tests\Helpers\Stubs\CrudExample;

class ValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group Helpers
     * @group Validator
     */
    public function testExtractTheCorrectFields()
    {
        $exampleCrud = new CrudExample();
        $body = [
            'banana' => 'nana',
            'stuff' => 'nope',
            'stuff1' => 'nope'
        ];
        $savedFields = Validator::validateAndCreate(
            $body,
            $exampleCrud,
            [
                'stuff1' => 'hey'
            ]
        );

        $this->assertEquals(
            [
                'banana' => 'nana',
                'stuff1' => 'hey'
            ],
            $savedFields
        );
    }

}

<?php


use App\Lib\Helpers\ValidationHelper;
use Tests\Helpers\Stubs\CrudExample;

class ValidationHelperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group Helpers
     * @group ValidationHelper
     */
    public function testExtractTheCorrectFields()
    {
        $exampleCrud = new CrudExample();
        $body = [
            'banana' => 'nana',
            'stuff' => 'nope',
            'stuff1' => 'nope'
        ];
        $savedFields = ValidationHelper::validateAndCreate(
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

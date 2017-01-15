<?php

namespace Adelowo\Reeval\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use function Adelowo\Reeval\validate;
use function Adelowo\Reeval\parseValidatorRules;

class ReevalValidatorTest extends TestCase
{

    public function testItParsesMultipleRules()
    {

        $rule = "fullname:length=>3|50,email";

        $expectedValues = [
            "index" => "fullname",
            "rules" => [
                [
                    0 => "length",
                    1 => '3|50'
                ],
                [
                    0 => "email"
                ]
            ]
        ];

        $this->assertSame($expectedValues, parseValidatorRules($rule));
    }

    public function testItParsesASingleRule()
    {
        $rule = "fullname:length=>3|50";

        $expected = [
            "index" => "fullname",
            "rules" => [
                [
                    0 => "length",
                    1 => "3|50"
                ]
            ]
        ];

        $this->assertSame($expected, parseValidatorRules($rule));
    }

    public function testEmailRuleWorksCorrectly()
    {
        $_POST['mail'] = "me@lanreadelowo.com";

        $validator = validate(["mail:email"]);

        $this->assertTrue($validator->passes());
    }

    public function testEmailRuleFailsBecauseOfInvalidData()
    {
        $_POST['mail'] = "some.ss";

        $validator = validate(["mail:email"]);

        $this->assertTrue($validator->fails());

        $this->assertSame(1, $validator->getErrors()->count());
    }

    public function testLengthRuleWorksCorrectly()
    {

        $_POST['username'] = "therealclown";
        $_POST['fullname'] = "Lanre Adelowo";
        $_POST['hobby'] = "Trolling";

        $validator = validate([
            "fullname:length=>5|50",
            "username:length=>3|20",
            "hobby:length=>4" //rule without a max length
        ]);

        $this->assertTrue($validator->passes());
    }

    public function testLengthRuleFailsBecauseOfInvalidData()
    {
        $_POST['username'] = "OX";
        $_POST['fullname'] = "Lanre";
        $_POST['hobby'] = "naff";

        $validator = validate([
            "fullname:length=>10|50",
            "username:length=>3|20",
            "hobby:length=>5" //rule without a max length
        ]);

        $this->assertFalse($validator->passes());

        $errors = $validator->getErrors();

        $this->assertSame(3, $errors->count());
    }

    public function testMultipleRules()
    {
        $_POST['mail'] = "me@lanreadelowo.com";
        $_POST['secondary_mail'] = "adelowomailbox@gmail.com";

        $rules = [
            "mail:length=>3|20,email",
            "secondary_mail:length=>10,email"
        ];

        $validator = validate($rules);

        $this->assertTrue($validator->passes());
    }

    public function testAnUnexpectedRuleIsEncountered()
    {
        $_POST['name'] = "Lanre Adelowo";

        $rules = ["name:length=>3|60,non-existent-rule"];

        $this->expectException(Exception::class);

        validate($rules);
    }
}

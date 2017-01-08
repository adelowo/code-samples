<?php

namespace Adelowo\Reeval;

use Countable;
use Exception;
use InvalidArgumentException;

function validator()
{

    return new class
    {

        public function __construct()
        {
            $this->errors = errorBag();
        }

        public function passes()
        {
            return !$this->fails();
        }

        public function fails()
        {
            return $this->errors->count() > 0;
        }

        public function getErrors()
        {
            return $this->errors;
        }
    };
}

function errorBag(array $defaultErrors = [])
{

    return new class implements Countable
    {

        protected $values;

        public function __construct(array $defaultErrors = [])
        {
            $this->values = $defaultErrors;
        }

        public function add(string $index, string $message)
        {
            $this->values[$index] = $message;
        }

        public function get(string $index)
        {

            if ($this->has($index)) {
                return $this->values[$index];
            }

            throw new InvalidArgumentException(
                "{$index} does not exst in this bag"
            );
        }

        public function has(string $index)
        {
            return array_key_exists($index, $this->all());
        }

        public function count()
        {
            return count($this->values);
        }

        public function all()
        {
            return $this->values;
        }
    };
}

function validate(array $rules)
{
    $validator = validator();
    $errorBag = $validator->getErrors();

    foreach ($rules as $rule) {

        $parsedRules = parse_validator_rules($rule);

        foreach ($parsedRules['rules'] as $parsedRule) {

            switch ($parsedRule[0]) {

                case "length":
                    validateLengthRule($parsedRules, $errorBag);
                    continue;

                case "email" :
                    validateEmailRule($parsedRules, $errorBag);
                    continue;

                default :
                    throw_unknown_rule_exception($parsedRule[0]);
            }
        }
    }

    return $validator;
}

function parse_validator_rules(string $index)
{

    $explodedRule = explode(":", $index);

    $index = $explodedRule[0];

    foreach (explode(",", $explodedRule[1]) as $rule) {
        $rules[] = explode("=>", $rule);
    }

    return [
        "index" => $index,
        "rules" => $rules
    ];
}


function validateLengthRule(array $ruleData, $errorBag)
{
    $index = $ruleData['index'];

    //could have resorted to `list()` here but not all length rules would specify a max value.
    //This is to prevent an index error.
    //index 0 would hold the minimum while index 1 would hold the max value - if any was given
    $minAndMax = explode("|", $ruleData['rules'][0][1]);

    if (mb_strlen($_POST[$index]) < $minAndMax[0]) {
        $errorBag->add(
            $index,
            "The field, {$index} should not contain a value lower than {$minAndMax[0]} in length"
        );
    }

    if (isset($minAndMax[1])) {
        if (mb_strlen($_POST[$index]) > $minAndMax[1]) {
            $errorBag->add(
                $index,
                "The field, {$index} should not contain a value greater than {$minAndMax[1]} in length"
            );
        }
    }
}

function validateEmailRule(array $ruleData, $errorBag)
{
    $index = $ruleData['index'];

    if (!filter_var($_POST[$index], FILTER_VALIDATE_EMAIL)) {
        $errorBag->add($index, "Please provide a valid email address");
    }
}

function throw_unknown_rule_exception(string $ruleName)
{
    throw new Exception(
        "The rule {$ruleName} doesn't exist on this validator. 
        Go hunt down a library on packagist."
    );
}

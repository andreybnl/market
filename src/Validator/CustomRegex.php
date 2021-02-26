<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraints\Regex;

class CustomRegex extends Regex
{
    public $message = "USE ONLY '*' '/' and digits!";
}
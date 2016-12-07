<?php

namespace Adelowo\Github;

use Exception;

class InvalidResponseException extends Exception
{
    const MESSAGE = "Only a status code of 200 is acceptable";
}

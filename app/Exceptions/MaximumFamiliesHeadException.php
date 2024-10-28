<?php

namespace App\Exceptions;

use Exception;

class MaximumFamiliesHeadException extends Exception
{
    protected $message = 'Maximum families head reached';
}

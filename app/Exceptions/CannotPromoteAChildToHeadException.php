<?php

namespace App\Exceptions;

use Exception;

class CannotPromoteAChildToHeadException extends Exception
{
    protected $message = 'Cannot promote a child to head';
}

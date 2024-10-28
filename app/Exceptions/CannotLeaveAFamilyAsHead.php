<?php

namespace App\Exceptions;

use Exception;

class CannotLeaveAFamilyAsHead extends Exception
{
    protected $message = 'Cannot leave a family as head';
}

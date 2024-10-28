<?php

namespace App\Exceptions;

use Exception;

class CannotLeaveFamily extends Exception
{
    protected $message = 'Citizen cannot leave family';
}

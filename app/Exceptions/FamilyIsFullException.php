<?php

namespace App\Exceptions;

use Exception;

class FamilyIsFullException extends Exception
{
    protected $message = 'Family is full';
}

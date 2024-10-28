<?php

namespace App\Exceptions;

use Exception;

class CitizenIsNotPartOfTheFamilyException extends Exception
{
    protected $message = 'Citizen is not part of the family';
}

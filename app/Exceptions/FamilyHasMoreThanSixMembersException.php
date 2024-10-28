<?php

namespace App\Exceptions;

use Exception;

class FamilyHasMoreThanSixMembersException extends Exception
{
    protected $message = 'Family has more than 6 members';
}

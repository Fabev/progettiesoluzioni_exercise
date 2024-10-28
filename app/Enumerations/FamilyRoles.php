<?php

namespace App\Enumerations;

enum FamilyRoles: string {
    case PARENT = 'parent';
    case TUTOR = 'tutor';
    case CHILD = 'child';
}

<?php

namespace App\Enumerations;

enum FamilyRoles: string {
    case PARENT = 'parent';
    case TUTOR = 'tutor';
    case CHILD = 'child';

    /**
     * Get a random role value
     *
     * @param FamilyRoles ...$exclude
     * @return string
     */
    public static function getRandom(self ...$exclude) : string {
        $roleValues = array_column(self::cases(), 'value');

        foreach ($exclude as $value) {
            unset($roleValues[array_search($value->value, $roleValues)]);
        }

        return $roleValues[array_rand($roleValues)];
    }
}

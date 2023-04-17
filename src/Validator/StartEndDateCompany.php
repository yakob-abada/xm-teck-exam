<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class StartEndDateCompany extends Constraint
{
    public string $startDateEndDateConstraintMsg = 'EndDate should be after or same of StartDate';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
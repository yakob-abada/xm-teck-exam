<?php

namespace App\Validator;

use App\Model\Company;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class StartEndDateCompanyValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof Company) {
            throw new UnexpectedValueException($value, Company::class);
        }

        if (!$constraint instanceof StartEndDateCompany) {
            throw new UnexpectedValueException($constraint, StartEndDateCompany::class);
        }

        if ($value->getStartDate() > $value->getEndDate()) {
            $this->context
                ->buildViolation($constraint->startDateEndDateConstraintMsg)
                ->atPath('endDate')
                ->addViolation();
        }
    }
}
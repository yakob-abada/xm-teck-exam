<?php

namespace App\Validator;

use App\Model\Company;
use App\Service\NasdaqService;
use App\Service\SymbolNotFoundException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SymbolCompanyValidator extends ConstraintValidator
{
    public function __construct(
        private readonly NasdaqService $companyService
    ) {
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var SymbolCompany $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        try {
            $company = new Company();
            $company->setSymbol($value);
            $this->companyService->get($company);

            return;
        } catch (SymbolNotFoundException $exception) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}

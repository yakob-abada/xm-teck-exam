<?php

namespace App\Tests\Validator;


use App\Model\Company;
use App\Validator\StartEndDateCompany;
use App\Validator\StartEndDateCompanyValidator;
use App\Validator\SymbolCompany;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class StartEndDateCompanyValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): ConstraintValidatorInterface
    {
        return new StartEndDateCompanyValidator();
    }

    public function testNullException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->validator->validate(null, new StartEndDateCompany());
    }

    public function testWrongConstraint()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->validator->validate(new Company(), new SymbolCompany());
    }

    public function testValidValues()
    {
        $company = new Company();
        $company
            ->setStartDate(new \DateTime())
            ->setEndDate(new \DateTime())
        ;
        $this->validator->validate($company, new StartEndDateCompany());

        $this->assertNoViolation();
    }

    public function testInvalidValues()
    {
        $constraint = new StartEndDateCompany([
            'startDateEndDateConstraintMsg' => 'myMessage',
        ]);

        $company = new Company();
        $company
            ->setStartDate(new \DateTime('2020-10-12'))
            ->setEndDate(new \DateTime('2020-10-10'))
        ;

        $this->validator->validate($company, $constraint);
        $this->setPropertyPath('property.path.endDate');

        $this
            ->buildViolation('myMessage')
            ->atPath('property.path.endDate')
            ->assertRaised();
    }
}
<?php

namespace App\Tests\Validator;


use App\Service\Nasdaq;
use App\Service\SymbolNotFoundException;
use App\Validator\SymbolCompany;
use App\Validator\SymbolCompanyValidator;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class SymbolCompanyValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): ConstraintValidatorInterface
    {
        $nasdaq = $this->createMock(Nasdaq::class);
        $nasdaq
            ->method('get')
            ->willThrowException(new SymbolNotFoundException('test'));

        return new SymbolCompanyValidator($nasdaq);
    }

    public function testNullIsValid()
    {
        $this->validator->validate(null, new SymbolCompany());

        $this->assertNoViolation();
    }

    public function testBlankIsValid()
    {
        $this->validator->validate('', new SymbolCompany());

        $this->assertNoViolation();
    }

    public function testInvalidValues()
    {
        $constraint = new SymbolCompany([
            'message' => 'myMessage',
        ]);

        $this->validator->validate('test', $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', 'test')
            ->assertRaised();
    }
}
<?php

namespace App\Tests\Form\Type;

use App\Form\Type\CompanyType;
use App\Model\Company;
use Symfony\Component\Form\Test\TypeTestCase;

class CompanyTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'symbol' => 'AMRN',
            'email' => 'test@test.com',
            'startDate' => '2020-01-01',
            'endDate' => '2020-01-31'
        ];

        $model = new Company();
        $form = $this->factory->create(CompanyType::class, $model);

        $expected = new Company();
        $expected
            ->setSymbol('AMRN')
            ->setEmail('test@test.com')
            ->setStartDate(new \DateTime('2020-01-01'))
            ->setEndDate(new \DateTime('2020-01-31'));

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $model);
    }
}
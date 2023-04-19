<?php

namespace App\Tests\Service;

use App\Model\Company;
use App\Service\HistoricalQuotes;
use App\Service\Nasdaq;
use App\Service\NewCompanyNotification;
use App\Service\NewCompanySubmissionMediator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NewCompanySubmissionMediatorTest extends TestCase
{
    public function testMediate(): void
    {
        // Given
        $company = new Company();
        $company
            ->setStartDate(new \DateTime('2020-01-01'))
            ->setEndDate(new \DateTime('2020-01-31'))
            ->setSymbol('AMRN')
            ->setEmail('reciever@test.com');


        $newCompanyNotification = $this->createMock(NewCompanyNotification::class);
        $newCompanyNotification
            ->expects($this->once())
            ->method('send')
            ->with($company);

        $historicalQuote = $this->createMock(HistoricalQuotes::class);
        $historicalQuote
            ->expects($this->once())
            ->method('getBetweenRange')
            ->with($company)
            ->willReturn([
                'prices' => []
            ]);

        $nasdaq = $this->createMock(Nasdaq::class);
        $nasdaq
            ->expects($this->once())
            ->method('get')
            ->with($company)
            ->willReturn([
                'Company Name' => 'iShares MSCI All Country Asia Information Technology Index Fund',
                'Financial Status' => 'N',
                'Market Category' => 'G',
                'Round Lot Size' => 100,
                'Security Name' => 'iShares MSCI All Country Asia Information Technology Index Fund',
                'Symbol' => 'AAIT',
                'Test Issue' => 'N'

            ]);

        // When
        $sut = new NewCompanySubmissionMediator($nasdaq, $newCompanyNotification, $historicalQuote);
        $result = $sut->mediate($company);

        // Then
        $this->assertEquals([
            'prices' => []
        ], $result);
        $this->assertEquals( 'iShares MSCI All Country Asia Information Technology Index Fund', $company->getName());
    }
}

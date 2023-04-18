<?php

namespace App\Tests\Service;

use App\Model\Company;
use App\Service\Nasdaq;
use App\Service\NewCompanyNotification;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NewCompanyNotificationServiceTest extends TestCase
{
    public function testMailIsSentAndContentIsOk(): void
    {
        // Given
        $email = (new Email())
            ->from('sender@test.com')
            ->to('reciever@test.com')
            ->subject('submitted Company Symbol = AMRN => Company’s Name = iShares MSCI All Country Asia Information Technology Index Fund')
            ->text('From 2020-01-01 to 2020-01-31');

        $symfonyMailer = $this->createMock(MailerInterface::class);
        $symfonyMailer
            ->expects($this->once())
            ->method('send')
            ->with($email);

        $company = new Company();
        $company
            ->setStartDate(new \DateTime('2020-01-01'))
            ->setEndDate(new \DateTime('2020-01-31'))
            ->setSymbol('AMRN')
            ->setEmail('reciever@test.com')
            ->setName('iShares MSCI All Country Asia Information Technology Index Fund');
        ;

        // When
        $sut = new NewCompanyNotification('sender@test.com', $symfonyMailer);
        $sut->send($company);
    }
}
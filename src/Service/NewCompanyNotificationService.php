<?php

namespace App\Service;

use App\Model\Company;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NewCompanyNotificationService
{
    public function __construct(
        private readonly string $sender,
        private readonly MailerInterface $mailer
    ) {
    }

    public function send(Company $company): void
    {
        $email = (new Email())
            ->from($this->sender)
            ->to($company->getEmail())
            ->subject('submitted Company Symbol = ' . $company->getSymbol() . ' => Company’s Name = Google')
            ->text(
                'From ' . $company->getStartDate()->format('Y-m-d') .
                ' to ' . $company->getEndDate()->format('Y-m-d')
            );

        $this->mailer->send($email);
    }
}
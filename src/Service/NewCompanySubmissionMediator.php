<?php

namespace App\Service;

use App\Model\Company;

class NewCompanySubmissionMediator
{
    public function __construct(
        private readonly Nasdaq $nasdaq,
        private readonly NewCompanyNotification $newCompanyNotification,
        private readonly HistoricalQuotes $historicalQuotes,
    ) {
    }

    public function mediate(Company $company): array
    {

        $this->setCompanyName($company);
        $this->newCompanyNotification->send($company);

        return $this->historicalQuotes->getBetweenRange($company);
    }

    private function setCompanyName(Company $company): void
    {
        $result = $this->nasdaq->get($company);
        $company->setName($result['Company Name']);
    }
}

<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as CompanyAssert;

#[CompanyAssert\StartEndDateCompany]
class Company
{
    #[Assert\NotBlank]
    #[CompanyAssert\SymbolCompany]
    private ?string $symbol = null;

    private ?\DateTimeInterface $startDate = null;

    private ?\DateTimeInterface $endDate = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}

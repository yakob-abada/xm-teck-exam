<?php

namespace App\Tests\Service;

use App\Entity\Company;
use App\Service\NasdaqService;
use App\Service\SymbolNotFoundException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class NasdaqServiceTest extends TestCase
{
    public function testRetrievingData(): void
    {
        $bodyJson = '[{
                "Company Name": "iShares MSCI All Country Asia Information Technology Index Fund",
                "Financial Status": "N",
                "Market Category": "G",
                "Round Lot Size": 100,
                "Security Name": "iShares MSCI All Country Asia Information Technology Index Fund",
                "Symbol": "AAIT",
                "Test Issue": "N"
            },
            {
                "Company Name": "American Airlines Group, Inc.",
                "Financial Status": "N",
                "Market Category": "Q",
                "Round Lot Size": 100,
                "Security Name": "American Airlines Group, Inc. - Common Stock",
                "Symbol": "AAL",
                "Test Issue": "N"
        }]';

        $result = [
            'Company Name' => 'American Airlines Group, Inc.',
            'Financial Status' => 'N',
            'Market Category' => 'Q',
            'Round Lot Size' => 100,
            'Security Name' => 'American Airlines Group, Inc. - Common Stock',
            'Symbol' => 'AAL',
            'Test Issue' => 'N'
        ];

        $company = new Company();
        $company->setSymbol('AAL');

        $responses = [
            new MockResponse($bodyJson),
        ];

        $client = new MockHttpClient($responses);

        $sut = new NasdaqService($client);

        $this->assertEquals($result, $sut->get($company));
    }

    public function testSymbolNotFound(): void
    {
        $bodyJson = '[
            {
                "Company Name": "American Airlines Group, Inc.",
                "Financial Status": "N",
                "Market Category": "Q",
                "Round Lot Size": 100,
                "Security Name": "American Airlines Group, Inc. - Common Stock",
                "Symbol": "AAL",
                "Test Issue": "N"
        }]';

        $company = new Company();
        $company->setSymbol('TEST');

        $responses = [
            new MockResponse($bodyJson),
        ];

        $client = new MockHttpClient($responses);

        $sut = new NasdaqService($client);

        $this->expectException(SymbolNotFoundException::class);
        $sut->get($company);
    }
}

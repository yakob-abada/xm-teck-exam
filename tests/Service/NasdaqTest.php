<?php

namespace App\Tests\Service;

use App\Model\Company;
use App\Service\Nasdaq;
use App\Service\SymbolNotFoundException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\TraceableAdapter;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\Cache\CacheInterface;

class NasdaqTest extends TestCase
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

        $result = [[
            'Company Name' => 'American Airlines Group, Inc.',
            'Financial Status' => 'N',
            'Market Category' => 'Q',
            'Round Lot Size' => 100,
            'Security Name' => 'American Airlines Group, Inc. - Common Stock',
            'Symbol' => 'AAL',
            'Test Issue' => 'N'
        ]];

        $company = new Company();
        $company->setSymbol('AAL');

        $responses = [
            new MockResponse($bodyJson),
        ];

        $client = new MockHttpClient($responses);

        $cache = $this->createMock(CacheInterface::class);
        $cache
            ->expects($this->once())
            ->method('get')
            ->willReturn($result);

        $sut = new Nasdaq($client, $cache);

        $this->assertEquals($result[0], $sut->get($company));
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

        $result = [[
            'Company Name' => 'American Airlines Group, Inc.',
            'Financial Status' => 'N',
            'Market Category' => 'Q',
            'Round Lot Size' => 100,
            'Security Name' => 'American Airlines Group, Inc. - Common Stock',
            'Symbol' => 'AAL',
            'Test Issue' => 'N'
        ]];

        $company = new Company();
        $company->setSymbol('TEST');

        $responses = [
            new MockResponse($bodyJson),
        ];

        $client = new MockHttpClient($responses);

        $cache = $this->createMock(CacheInterface::class);
        $cache
            ->expects($this->once())
            ->method('get')
            ->willReturn($result);

        $sut = new Nasdaq($client, $cache);

        $this->expectException(SymbolNotFoundException::class);
        $sut->get($company);
    }
}

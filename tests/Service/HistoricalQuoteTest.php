<?php

namespace App\Tests\Service;

use App\Model\Company;
use App\Service\HistoricalQuotes;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class HistoricalQuoteTest extends TestCase
{
    public function testRetrievingData(): void
    {
        $bodyJson = '{
            "prices": [{
                "date": 1681747268,
                "open": 1.350000023841858,
                "high": 1.3694000244140625,
                "low": 1.3300000429153442,
                "close": 1.350000023841858,
                "volume": 815162,
                "adjclose": 1.350000023841858
            }],
            "isPending": false,
            "firstTradeDate": 733674600,
            "id": "",
            "timeZone": {
                "gmtOffset": -14400
            },
            "eventsData": []
        }
        ';

        $result = [
            'prices' => [
                [
                    'date' => 1681747268,
                    'open' => 1.350000023841858,
                    'high' => 1.3694000244140625,
                    'low' => 1.3300000429153442,
                    'close' => 1.350000023841858,
                    'volume' => 815162,
                    'adjclose' => 1.350000023841858
                ]
            ],
            'isPending' => false,
            'firstTradeDate' => 733674600,
            'id' =>'',
            'timeZone' => ['gmtOffset' => -14400],
            'eventsData' => []
        ];

        $company = new Company();
        $company
            ->setSymbol('AMRN')
            ->setStartDate(new \DateTime('2023-04-11'))
            ->setEndDate(new \DateTime('2023-04-18'))
        ;

        $responses = [
            new MockResponse($bodyJson),
        ];

        $client = new MockHttpClient($responses);

        $sut = new HistoricalQuotes($client);

        $this->assertEquals($result, $sut->getBetweenRange($company));
    }
}

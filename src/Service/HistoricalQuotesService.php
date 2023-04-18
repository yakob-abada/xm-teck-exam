<?php

namespace App\Service;

use App\Model\Company;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HistoricalQuotesService
{
    public function __construct(
        private readonly HttpClientInterface $rapidApiClient,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function get(Company $company): array
    {
        $response = $this
            ->rapidApiClient
            ->request('GET', '/stock/v3/get-historical-data', [
                    // these values are automatically encoded before including them in the URL
                    'query' => [
                        'symbol' => $company->getSymbol(),
                        'region' => 'US',
                    ],
                ]
            );

        return $response->toArray();
    }
}
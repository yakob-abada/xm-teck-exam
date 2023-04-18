<?php

namespace App\Service;

use App\Model\Company;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Nasdaq
{
    public function __construct(
        private readonly HttpClientInterface $datahub,
        private readonly CacheInterface $cache
    ) {
    }

    /**
     * @param Company $company
     * @return array
     * @throws SymbolNotFoundException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function get(Company $company): array
    {
        $result = $this->cache->get('mixes_data', function() use ($company) {
            $response = $this->datahub->request('GET', '/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json');

            return $response->toArray();
        });

        foreach ($result as $item) {
            if ($item['Symbol'] === $company->getSymbol()) {
                return $item;
            }
        }

        throw new SymbolNotFoundException($company->getSymbol());
    }
}

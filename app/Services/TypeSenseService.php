<?php

namespace App\Services;

use Typesense\Client;

class TypeSenseService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'api_key' => config('typesense.api_key'),
            'nodes' => [
                [
                    'host' => config('typesense.host'),
                    'port' => config('typesense.port'),
                    'protocol' => config('typesense.protocol'),
                ],
            ],
            'connection_timeout_seconds' => 2,
        ]);
    }

    public function search($collection, $query, $params = [])
    {
        return $this->client->collections[$collection]->documents->search(array_merge([
            'q' => $query,
            'query_by' => 'name,description',
        ], $params));
    }

    public function getClient()
    {
        return $this->client;
    }
}

<?php

namespace App\Service;

use App\Constants\Endpoints;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
class PdfGenerationService
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Convertit une URL en PDF en utilisant le service Gotenberg
     *
     * @param string $url L'URL à convertir en PDF
     * @return string Le contenu du PDF
     *
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function fromUrl(string $url): string
    {
        return $this->client->request(
            'GET',
            $_ENV['API_URL'].Endpoints::GENERATE_FROM_URL,
            [
                'query' => [
                    'url' => $url
                ]
            ]
        )->getContent();
    }
}
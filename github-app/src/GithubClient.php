<?php

namespace Adelowo\Github;

use GuzzleHttp\Client;
use function GuzzleHttp\json_decode;

class GithubClient
{

    const BASE_API_LINK = "https://api.github.com/";

    protected $httpClient;

    public function __construct(Client $client)
    {
        $this->httpClient = $client;
    }

    public function getUserProfile(string $userName)
    {
        $response = $this->get("users/{$userName}");

        if (200 !== $response->getStatusCode()) {
            throw $this->throwInvalidResponseException();
        }

        return json_decode($response->getBody(), true);
    }

    protected function get(string $relativeUrl)
    {
        return $this->httpClient->get(self::BASE_API_LINK . $relativeUrl);
    }

    protected function throwInvalidResponseException()
    {
        return new InvalidResponseException(
            InvalidResponseException::MESSAGE
        );
    }

    public function getUserRepositories(string $userName)
    {
        $response = $this->get("users/{$userName}/repos");

        if (200 !== $response->getStatusCode()) {
            throw $this->throwInvalidResponseException();
        }

        return json_decode($response->getBody(), true);
    }
}

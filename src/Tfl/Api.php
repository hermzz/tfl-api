<?php namespace Tfl;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Request;

use GuzzleHttp\Exception\ServerException;

class Api
{
    const BASE_URL = 'http://api.tfl.gov.uk';

    protected $client;
    protected $app_id;
    protected $app_key;

    public function __construct(Client $client, $app_id, $app_key)
    {
        $this->client = $client;
        $this->app_id = $app_id;
        $this->app_key = $app_key;
    }

    public function JourneyResults($from, $to, $via = null)
    {
        $url = '/Journey/JourneyResults/'.$from.'/to/'.$to;

        if ($via) {
            $url .= '/'.$via;
        }

        return $this->get($url);
    }

    public function get($url, $params = [])
    {
        $request = $this->client->createRequest(
            'GET',
            self::BASE_URL . $url
        );

        $params = $params + ['app_id' => $this->app_id, 'app_key' => $this->app_key];

        foreach ($params as $k => $v) {
            $request->getQuery()->set($k, $v);
        }

        return $this->handleRequest($request);
    }

    public function post($url, $params = [])
    {
        $request = $this->client->createRequest(
            'POST',
            self::BASE_URL . $url
        );

        $params = $params + ['app_id' => $this->app_id, 'app_key' => $this->app_key];

        foreach ($params as $k => $v) {
            $request->getQuery()->set($k, $v);
        }

        return $this->handleRequest($request);
    }

    protected function handleRequest(Request $request)
    {
        $response = $this->client->send($request);

        return $response->json();
    }
}

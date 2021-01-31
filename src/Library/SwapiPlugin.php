<?php

namespace App\Library;

class SwapiPlugin
{
    protected $client;
    private $debug;

    /**
     * Construct class
     */
    public function __construct($debug = false)
    {
        $this->client = new \GuzzleHttp\Client();
        $this->debug = $debug;
    }

    public function consultName($name)
    {
        try {
            return $this->client->get("https://swapi.dev/api/people/?search=$name");
        } catch (\Exception $e) {
            $e = $this->logErrorApi($e);
            return $e;
        }
        return [];
    }

    public function consult($url)
    {
        try {
            return $this->client->get($url);
        } catch (\Exception $e) {
            $e = $this->logErrorApi($e);
            return $e;
        }
        return [];
    }

    /**
     * Loga os erros da API
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return void
     */
    private function logErrorApi($response)
    {
        $data = [
            "status" => 500,
            "body" => "Não foi possível realizar a consulta"
        ];

        if (method_exists($response, "getStatusCode") && method_exists($response, "getBody")) {
            $data = [
                "status" => $response->getStatusCode(),
                "body" => $response->getBody()
            ];
        }

        if ($this->debug) {
            \Log::error("Plugin SOAP", $data);
        }

        return json_encode($data);
    }
}

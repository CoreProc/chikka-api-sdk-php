<?php

namespace Coreproc\Chikka;

use GuzzleHttp\Client;

class ChikkaClient
{

    /**
     * @var
     */
    private $clientId;

    /**
     * @var
     */
    private $secretKey;

    /**
     * @var
     */
    private $shortCode;

    /**
     * @var
     */
    public $client;

    /**
     * @param $clientId
     * @param $secretKey
     * @param $shortCode
     */
    public function __construct($clientId, $secretKey, $shortCode)
    {
        $this->clientId  = $clientId;
        $this->secretKey = $secretKey;
        $this->shortCode = $shortCode;


        $this->client = new Client();
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return mixed
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * @return mixed
     */
    public function getShortCode()
    {
        return $this->shortCode;
    }

}
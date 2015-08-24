<?php

namespace Coreproc\Chikka;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

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
     * @var LoggerInterface
     */
    public $logger;

    /**
     * @var
     */
    public $client;

    /**
     * Base URL of the Chikka API
     */
    const BASE_URL = 'https://post.chikka.com/smsapi/';

    public $loggingEnabled = false;

    /**
     * @param $clientId
     * @param $secretKey
     * @param $shortCode
     * @param LoggerInterface $logger
     */
    public function __construct($clientId, $secretKey, $shortCode, LoggerInterface $logger = null)
    {
        $this->clientId = $clientId;
        $this->secretKey = $secretKey;
        $this->shortCode = $shortCode;

        if ( ! empty($logger)) {
            $this->logger = $logger;
            $this->loggingEnabled = true;
        }

        $this->client = new Client([
            'base_uri' => self::BASE_URL
        ]);
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
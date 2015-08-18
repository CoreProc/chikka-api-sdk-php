<?php

namespace Coreproc\Chikka\Model;

use Coreproc\Chikka\ChikkaClient;
use GuzzleHttp\Exception\BadResponseException;
use Valitron\Validator;

class Sms
{

    /**
     * @var ChikkaClient
     */
    private $chikkaClient;


    /**
     * @var
     */
    private $messageId;


    /**
     * @var
     */
    private $mobileNumber;


    /**
     * @var
     */
    private $message;

    /**
     * @var string
     */
    private $chikkaRequestUrl = "https://post.chikka.com/smsapi/request";

    /**
     * @var string
     */
    private $messageType = "SEND";


    /**
     * @param ChikkaClient $chikkaClient
     */
    public function __construct(ChikkaClient $chikkaClient)
    {
        $this->chikkaClient = $chikkaClient;
    }


    /**
     * @return mixed
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * @return mixed
     */
    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param $messageId
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
    }

    /**
     * @param $mobileNumber
     */
    public function setMobileNumber($mobileNumber)
    {
        $this->mobileNumber = $mobileNumber;
    }

    /**
     * @param $message
     */
    public function setMessage($message)
    {
        $this->message = trim($message);
    }


    /**
     * @param array $params
     * @throws \Exception
     */
    public function send(array $params)
    {

        $this->validate($params);

        try {
            $response = $this->chikkaClient->client->post($this->chikkaRequestUrl,
                [
                    'body' =>
                        [
                            'message_type'  => $this->messageType,
                            'mobile_number' => $params['mobile_number'],
                            'message_id'    => $params['message_id'],
                            'message'       => $params['message'],
                            'shortcode'     => $this->chikkaClient->getShortCode(),
                            'client_id'     => $this->chikkaClient->getClientId(),
                            'secret_key'    => $this->chikkaClient->getSecretKey()
                        ]
                ]
            );

            $json = $response->json();

            return $json;

        } catch (BadResponseException $e) {
            throw new \Exception($e->getMessage());
        }

        return null;
    }


    private function validate($params)
    {
        $validator = new Validator($params);

        $validator->rules('required',
            [
                'message',
                'mobile_number',
                'shortcode'
            ],
            '');

        if ( ! $validator->validate()) {
            $errors = '';

            foreach ($validator->errors() as $key => $value) {
                $errors .= $key . 'is required .';
            }

            throw new \Exception($errors);
        }

    }

}
<?php

namespace Coreproc\Chikka\Model;

use Coreproc\Chikka\ChikkaClient;
use Exception;
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
     * @param string|null $messageId Unique ID (for at least 24 hours) you need to
     * generate. This will be used in tracking your Delivery Notifications. Max
     * length: 32 characters
     * @param string|null $mobileNumber Mobile number of the user whom you want to
     * send the message to.
     * @param string|null $message Contents of the message to be sent to the user.
     * Max length: 420 characters
     * @return array;
     * @throws Exception
     */
    public function send($messageId = null, $mobileNumber = null, $message = null)
    {
        if ( ! empty($messageId)) {
            $this->setMessageId($messageId);
        }
        if ( ! empty($mobileNumber)) {
            $this->setMobileNumber($mobileNumber);
        }
        if ( ! empty($message)) {
            $this->setMessage($message);
        }

        $params = [
            'message_id'    => $this->getMessageId(),
            'mobile_number' => $this->getMobileNumber(),
            'message'       => $this->getMessage(),
            'shortcode'     => $this->chikkaClient->getShortCode(),
            'client_id'     => $this->chikkaClient->getClientId(),
            'secret_key'    => $this->chikkaClient->getSecretKey(),
            'message_type'  => 'SEND'
        ];

        try {
            $this->validate($params);
        } catch (Exception $e) {
            throw $e;
        }

        try {
            $response = $this->chikkaClient->client->post($this->chikkaRequestUrl, [
                    'body' => [
                        'message_type'  => $params['message_type'],
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
            throw $e;
        }
    }


    private function validate($params)
    {
        $validator = new Validator($params);

        $validator->rule('required', [
            'message',
            'mobile_number',
            'shortcode',
            'client_id',
            'secret_key',
            'message_type'
        ]);

        if ( ! $validator->validate()) {
            $errors = '';

            foreach ($validator->errors() as $key => $value) {
                $errors .= $key . ' is required. ';
            }

            throw new \Exception($errors);
        }

    }

}
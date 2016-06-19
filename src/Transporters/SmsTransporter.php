<?php

namespace Coreproc\Chikka\Transporters;

use Coreproc\Chikka\ChikkaClient;
use Coreproc\Chikka\Contracts\SmsContract;
use Coreproc\Chikka\Contracts\SmsTransporterActionsContract;
use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use stdClass;

class SmsTransporter
{

    /**
     * @var ChikkaClient
     */
    protected $chikkaClient;

    /**
     * @var SmsContract
     */
    protected $sms;

    public function __construct(ChikkaClient $chikkaClient, SmsContract $smsContract)
    {
        $this->chikkaClient = $chikkaClient;
        $this->sms = $smsContract;
    }

    public function send(SmsTransporterActionsContract $smsTransporterActions = null)
    {
        if (empty($smsTransporterActions)) {
            $smsTransporterActions = new SmsTransporterActions($this->chikkaClient, $this->sms);
        }

        $smsTransporterActions->onStart();

        $httpClient = $this->chikkaClient->client;
        $response = null;

        try {
            $request = $httpClient->post('request', [
                'form_params' => [
                    'message_type'  => 'SEND',
                    'mobile_number' => $this->sms->getMobileNumber(),
                    'shortcode'     => $this->chikkaClient->getShortCode(),
                    'message_id'    => $this->sms->getMessageId(),
                    'message'       => $this->sms->getMessage(),
                    'client_id'     => $this->chikkaClient->getClientId(),
                    'secret_key'    => $this->chikkaClient->getSecretKey(),
                ],
            ]);

            $response = json_decode($request->getBody()->getContents());

        } catch (ClientException $e) {

            // 40x errors
            $message = $e->getMessage();

            if ($e->hasResponse()) {
                $response = json_decode($e->getResponse()->getBody()->getContents());
                $message = $response->description;
            }

            $exception = new Exception($message, $e->getCode());

            if ($e->getCode() == 401) {
                // there is something wrong with the chikka credentials.
                // mark this as error
                $smsTransporterActions->onError($exception);
            } else {
                // only possible reason here SHOULD be 400 bad request
                // which makes this SMS invalid
                $smsTransporterActions->onInvalid($exception);
            }

        } catch (ServerException $e) {

            // 50x errors
            if ($e->hasResponse()) {
                $response = json_decode($e->getResponse()->getBody()->getContents());

            } else {
                $response = $this->standardizeExceptionResponse($e);
            }

            $message = $response->description;
            $exception = new Exception($message, $e->getCode());

            $smsTransporterActions->onError($exception);

        } catch (ConnectException $e) {

            // networking error
            $response = $this->standardizeExceptionResponse($e);

            $message = $response->description;
            $exception = new Exception($message, $e->getCode());

            $smsTransporterActions->onError($exception);

        } catch (RequestException $e) {

            // any other errors
            if ($e->hasResponse()) {
                $response = json_decode($e->getResponse()->getBody()->getContents());
            } else {
                $response = $this->standardizeExceptionResponse($e);
            }

            $message = $response->description;
            $exception = new Exception($message, $e->getCode());

            $smsTransporterActions->onError($exception);

        }

        return $response;
    }

    /**
     * @param string $requestId Same transaction ID indicated in the message you received from Chikka.
     * @param string $requestCost Amount you desire to charge the user who will receive the message. This will be deducted from the user's actual load.
     * Possible values:
     * For SMART & Globe: FREE, P1.00, P2.50, P5.00, P10.00, P15.00
     * For SUN: FREE
     * If FREE will be used for charging, credits will be deducted based on the originating carrier of the user. It is Php 0.40 for Smart/Sun and Php 0.50 for Globe.
     * @param SmsTransporterActionsContract|null $smsTransporterActions
     * @return mixed|null|stdClass
     */
    public function reply($requestId, $requestCost = 'FREE', SmsTransporterActionsContract $smsTransporterActions = null)
    {
        // Transform request cost
        if (is_numeric($requestCost)) {
            if ($requestCost <= 0) {
                $requestCost = 'FREE';
            }
        }
        if (is_null($requestCost)) {
            $requestCost = 'FREE';
        }

        if (empty($smsTransporterActions)) {
            $smsTransporterActions = new SmsTransporterActions($this->chikkaClient, $this->sms);
        }

        $smsTransporterActions->onStart();

        $httpClient = $this->chikkaClient->client;
        $response = null;

        try {
            $request = $httpClient->post('request', [
                'form_params' => [
                    'message_type'  => 'REPLY',
                    'mobile_number' => $this->sms->getMobileNumber(),
                    'shortcode'     => $this->chikkaClient->getShortCode(),
                    'request_id'    => $requestId,
                    'message_id'    => $this->sms->getMessageId(),
                    'message'       => $this->sms->getMessage(),
                    'request_cost'  => $requestCost,
                    'client_id'     => $this->chikkaClient->getClientId(),
                    'secret_key'    => $this->chikkaClient->getSecretKey(),
                ],
            ]);

            $response = json_decode($request->getBody()->getContents());

        } catch (ClientException $e) {

            // 40x errors
            $message = $e->getMessage();

            if ($e->hasResponse()) {
                $response = json_decode($e->getResponse()->getBody()->getContents());
                $message = $response->description;
            }

            $exception = new Exception($message, $e->getCode());

            if ($e->getCode() == 401) {
                // there is something wrong with the chikka credentials.
                // mark this as error
                $smsTransporterActions->onError($exception);
            } else {
                // only possible reason here SHOULD be 400 bad request
                // which makes this SMS invalid
                $smsTransporterActions->onInvalid($exception);
            }

        } catch (ServerException $e) {

            // 50x errors
            if ($e->hasResponse()) {
                $response = json_decode($e->getResponse()->getBody()->getContents());

            } else {
                $response = $this->standardizeExceptionResponse($e);
            }

            $message = $response->description;
            $exception = new Exception($message, $e->getCode());

            $smsTransporterActions->onError($exception);

        } catch (ConnectException $e) {

            // networking error
            $response = $this->standardizeExceptionResponse($e);

            $message = $response->description;
            $exception = new Exception($message, $e->getCode());

            $smsTransporterActions->onError($exception);

        } catch (RequestException $e) {

            // any other errors
            if ($e->hasResponse()) {
                $response = json_decode($e->getResponse()->getBody()->getContents());
            } else {
                $response = $this->standardizeExceptionResponse($e);
            }

            $message = $response->description;
            $exception = new Exception($message, $e->getCode());

            $smsTransporterActions->onError($exception);

        }

        return $response;
    }

    private function standardizeExceptionResponse(RequestException $e)
    {
        $response = new stdClass();
        $response->status = $e->getCode();
        $response->description = $e->getMessage();

        return $response;
    }

}

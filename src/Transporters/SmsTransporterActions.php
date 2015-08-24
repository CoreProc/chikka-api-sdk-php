<?php

namespace Coreproc\Chikka\Transporters;

use Coreproc\Chikka\ChikkaClient;
use Coreproc\Chikka\Contracts\SmsContract;
use Coreproc\Chikka\Contracts\SmsTransporterActionsContract;
use Exception;

class SmsTransporterActions implements SmsTransporterActionsContract
{

    /**
     * @var ChikkaClient
     */
    protected $chikkaClient;

    /**
     * @var SmsContract
     */
    protected $sms;

    public function __construct(ChikkaClient $chikkaClient, SmsContract $sms)
    {
        $this->chikkaClient = $chikkaClient;
        $this->sms = $sms;
    }

    /**
     * This is called at the start of sending the SMS
     *
     * @return mixed
     */
    public function onStart()
    {
        if ($this->chikkaClient->loggingEnabled) {
            $this->chikkaClient->logger->info("[MessageId#{$this->sms->getMessageId()}] Attempting to send SMS ...");
        }
    }

    /**
     * This is called when the SMS being sent is invalid. Use this
     * method to invalidate the SMS. Attempting to send this SMS
     * again is futile.
     *
     * @param Exception $exception
     * @return mixed
     */
    public function onInvalid(Exception $exception)
    {
        if ($this->chikkaClient->loggingEnabled) {
            $this->chikkaClient->logger->error("[MessageId#{$this->sms->getMessageId()}] Invalid SMS - " . $exception->getCode() . ' - ' . $exception->getMessage());
        }
    }

    /**
     * This method is called when a server exception is encountered.
     * This means that the SMS should be marked as pending and sending
     * should be retried.
     *
     * @param Exception $exception
     * @return mixed
     */
    public function onError(Exception $exception)
    {
        if ($this->chikkaClient->loggingEnabled) {
            $this->chikkaClient->logger->error("[MessageId#{$this->sms->getMessageId()}] Error sending SMS - " . $exception->getCode() . ' - ' . $exception->getMessage());
        }
    }

    /**
     * Called when everything goes well.
     *
     * @return mixed
     */
    public function onSuccess()
    {
        if ($this->chikkaClient->loggingEnabled) {
            $this->chikkaClient->logger->info("[MessageId#{$this->sms->getMessageId()}] Accepted");
        }
    }
}
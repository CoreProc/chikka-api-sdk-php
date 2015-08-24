<?php

namespace Coreproc\Chikka\Contracts;

use Coreproc\Chikka\ChikkaClient;
use Exception;

interface SmsTransporterActionsContract
{

    /**
     * This object should accept a ChikkaClient and an object that implents SmsContract.
     *
     * @param ChikkaClient $chikkaClient
     * @param SmsContract $sms
     */
    public function __construct(ChikkaClient $chikkaClient, SmsContract $sms);

    /**
     * This is called at the start of sending the SMS
     */
    public function onStart();

    /**
     * This is called when the SMS being sent is invalid. Use this
     * method to invalidate the SMS. Attempting to send this SMS
     * again is futile.
     *
     * @param Exception $exception
     */
    public function onInvalid(Exception $exception);

    /**
     * This method is called when a server exception is encountered.
     * This means that the SMS should be marked as pending and sending
     * should be retried.
     *
     * @param Exception $exception
     */
    public function onError(Exception $exception);

    /**
     * Called when everything goes well.
     */
    public function onSuccess();

}
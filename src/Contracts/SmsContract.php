<?php

namespace Coreproc\Chikka\Contracts;

interface SmsContract
{

    /**
     * @return string
     */
    public function getMessageId();

    /**
     * [Required] Unique ID (for at least 24 hours) you need to generate. This will be
     * used in tracking your Delivery Notifications.
     *
     * @param $messageId
     * @return mixed
     */
    public function setMessageId($messageId);

    /**
     * @return string
     */
    public function getMobileNumber();

    /**
     * [Required] Mobile number of the user whom you want to send the message to.
     * You should invoke this method at least once before sending the SMS.
     *
     * @param string $mobileNumber
     * @return bool returns true if the recipient was successfully added as a recipient
     * false if otherwise
     */
    public function setMobileNumber($mobileNumber);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * [Required] Contents of the message to be sent to the user.
     *
     * @param string $message
     */
    public function setMessage($message);

}
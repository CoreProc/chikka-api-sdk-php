<?php

namespace Coreproc\Chikka\Models;

use Coreproc\Chikka\Contracts\SmsContract;
use Coreproc\MsisdnPh\Msisdn;

class Sms implements SmsContract
{

    private $messageId;

    private $mobileNumber;

    private $message;

    public function __construct($messageId = null, $mobileNumber = null, $message = null)
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
    }

    /**
     * @return string
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * [Required] Unique ID (for at least 24 hours) you need to generate. This will be
     * used in tracking your Delivery Notifications.
     *
     * @param $messageId
     * @return mixed
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
    }

    /**
     * @return string
     */
    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }

    /**
     * [Required] Mobile number of the user whom you want to send the message to.
     * You should invoke this method at least once before sending the SMS.
     *
     * @param string $mobileNumber
     * @return bool returns true if the recipient was successfully added as a recipient
     * false if otherwise
     */
    public function setMobileNumber($mobileNumber)
    {
        // We should format the number if it's valid
        // If it's not a valid mobile number, just let it be and let the API handle it.

        $this->mobileNumber = $mobileNumber;

        if (Msisdn::validate($mobileNumber)) {
            $msisdn = new Msisdn($mobileNumber);
            $msisdn->setCountryPrefix('63');
            $this->mobileNumber = $msisdn->get(true);
        }
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * [Required] Contents of the message to be sent to the user.
     *
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

}
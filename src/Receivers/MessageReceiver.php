<?php

namespace Coreproc\Chikka\Receivers;

use Carbon\Carbon;

class MessageReceiver
{

    /**
     * POST value from chikka
     * @param array $post
     */
    public function __construct(array $post)
    {
        foreach ($post as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function getMessageType()
    {
        return $this->message_type;
    }

    public function getMobileNumber()
    {
        return $this->mobile_number;
    }

    public function getShortCode()
    {
        return $this->shortcode;
    }

    public function getRequestId()
    {
        return $this->request_id;
    }

    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return timestamp carbon
     */
    public function getTimeStamp()
    {
        return Carbon::createFromTimestamp($this->timestamp);
    }

}
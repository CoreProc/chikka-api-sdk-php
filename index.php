<?php

require 'vendor/autoload.php';

$clientId = "7185032eaa78fc76e7b732e53801e5e07cc2da2b160f0d9abbaae099f503e4e2";
$secretKey = "ae51ed2ea6716f55c87a510d113d4e65f6763097ab6b3e1490dcf5d9c37b7b41";
$shortCode = "2929068269";

$chikkaClient = new \Coreproc\Chikka\ChikkaClient($clientId, $secretKey, $shortCode);

$sms = new \Coreproc\Chikka\Model\Sms($chikkaClient);

$sms->setMessageId('1');
$sms->setMessage('Hello this is a test message');
$sms->setMobileNumber('+639175496912');

try {
    $sms->send();
} catch (Exception $e) {
    dump($e->getMessage());
    return;
}

echo 'success';
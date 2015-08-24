Chikka API SDK for PHP
=====================

A PHP library for interacting with the Chikka API for sending and receiving SMS.

Note: This is not an official Chikka library

## Install

Run the following command at the root of your project (assuming you have Composer and a composer.json file already)

```bash
composer require coreproc/chikka-api-sdk 0.1.*
```

## Usage

### Sending SMS

Basic sending of an SMS can be summarized in four steps:

1. Instantiate a `ChikkaClient` object with your credentials and shortcode.
2. Instantiate an `Sms` object with your desired message.
3. Instantiate an `SmsTransporter` using the `ChikkaClient` and `Sms` objects you just created.
4. Send using the `SmsTransporter`

Here is an example:

```php
<?php

require 'vendor/autoload.php';

use Coreproc\Chikka\ChikkaClient;
use Coreproc\Chikka\Models\Sms;
use Coreproc\Chikka\Transporters\SmsTransporter;

$chikkaClient = new ChikkaClient('your-chikka-client-id', 'your-chikka-secret-key', 'your-chikka-shortcode');

$sms = new Sms('unique-message-id', 'mobile-number', 'your-message-here');

$smsTransporter = new SmsTransporter($chikkaClient, $sms);

$response = $smsTransporter->send();

print_r($response);
```

### Determining the response of an SMS

The `send()` method of the `SmsTransporter` class returns an object containing the response from Chikka's server. A sample successful response is below:

```
stdClass Object
(
    [status] => 200
    [message] => ACCEPTED
)
```

A sample error response would be as follows:

```
stdClass Object
(
    [status] => 400
    [message] => BAD REQUEST
    [description] => Inactive/Invalid Shortcode
)
```

However, determining the response of the SMS API from this standpoint would result in an archaic code base of and this would best be handled by a separate class.

You can implement an `SmsTransporterActionsContract` to a class to handle the responses. A sample class that implements `SmsTransporterActionsContract` is as follows:

```php
<?php

namespace Vendor\PackageName;

use Coreproc\Chikka\Contracts\SmsContract;
use Coreproc\Chikka\Contracts\SmsTransporterActionsContract;
use Exception;

class SampleSmsTransporterActions implements SmsTransporterActionsContract
{

    public function __construct(ChikkaClient $chikkaClient, SmsContract $sms)
    {
        // TODO: Implement __construct() method.
    }

    public function onStart()
    {
        // TODO: Implement onStart() method.
    }

    public function onInvalid(Exception $exception)
    {
        // TODO: Implement onInvalid() method.
    }

    public function onError(Exception $exception)
    {
        // TODO: Implement onError() method.
    }

    public function onSuccess()
    {
        // TODO: Implement onSuccess() method.
    }
}
```

The `SmsTransporterActionsContract` interface has four notable methods:

1. `onStart()` method is called at the start of sending an SMS

2. `onInvalid()` method is called when the SMS being sent is invalid. This usually means that one of your parameters are incorrect and resulted in a 400
response code from Chikka's server. Handle your SMS as necessary in this method. The `Exception` object passed here contains the code and the message of the
response and you can get them by using `$exception->getCode()` and `$exception->getMessage()`, respectively.

3. `onError()` method is called when a server exception is encountered. This usually means that the Chikka server responded with a 500 level response and your
SMS was not successfully transmitted anywhere. You should probably try sending your SMS again if this occurs.

4. `onSuccess()` method is called when an SMS was successfully accepted by Chikka's servers.

To use this `SampleSmsTransporterActions` class when sending an SMS, simply include it as the first parameter of the `send()` method. Here is an example:

```php
$chikkaClient = new ChikkaClient('your-chikka-client-id', 'your-chikka-secret-key', 'your-chikka-shortcode');

$sms = new Sms('unique-message-id', 'mobile-number', 'your-message-here');

$smsTransporter = new SmsTransporter($chikkaClient, $sms);

$sampleSmsTransporterActions = new SampleSmsTransporterActions($chikkaClient, $sms);

$response = $smsTransporter->send($sampleSmsTransporterActions);
```

*Note:* When using the `send()` method without a SmsTransporterActionsContract class, the default class that is used is `Coreproc\Chikka\Transporters\SmsTransporterActions`.
You can use that class as a reference for creating your own SmsTransporterActionsContract class.
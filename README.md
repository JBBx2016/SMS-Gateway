# SMS-Gateway
Library to send SMS using Eurobate gateway

## Installation using Composer
`composer require jbbx2016/sms-gateway`

## Basic usage

```
<?php

use JBBx2016\SMSGateway\Common\CountryCodes;
use JBBx2016\SMSGateway\Common\Exceptions\Exception;
use JBBx2016\SMSGateway\Common\PhoneNumber;
use JBBx2016\SMSGateway\Gateways\Eurobate\EurobateSendMessageResponse;
use JBBx2016\SMSGateway\Gateways\Eurobate\EurobateGateway;
use JBBx2016\SMSGateway\Payloads\SMSPayload;
use JBBx2016\SMSGateway\SMSGatewayApi;

$Gateway = new SMSGatewayApi();

$Gateway->AddGateway(new EurobateGateway('username', 'password'));

try {

    /** @var EurobateSendMessageResponse $Response */
    $Response = $Gateway->SendMessage(
        $SMS->GetSender(),
        new SMSPayload(
            new PhoneNumber(CountryCodes::Norway, '98765432'),
            "This is the message body"
        )
    );
    
    $EurobateId = $Response->Id;

} catch (Exception $Exception) {

    $this->_Logger->Error('Failed to process SMS - Exception: ' . get_class($Exception) . "(" . $Exception->getMessage() . ")");

}

```

## Exceptions

- `JBBx2016\SMSGateway\Common\Exceptions\Exception`: Base exception
  * `JBBx2016\SMSGateway\Common\Exceptions\GatewayEndpointConnectionFailedException`: Connection to gateway endpoint failed
  * `JBBx2016\SMSGateway\Common\Exceptions\NoGatewayFoundException`: Called if no appropriate gateway is found
  * `JBBx2016\SMSGateway\Gateways\Eurobate\EurobateException`: Exception for Eurobate gateway
    * `JBBx2016\SMSGateway\Gateways\Eurobate\Exceptions\IPNotAuthorizedEurobateException`: Eurobate specific error
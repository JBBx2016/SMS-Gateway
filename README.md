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
use JBBx2016\SMSGateway\Senders\TextSender;
use JBBx2016\SMSGateway\SMSGatewayApi;

$SMSGatewayApi = new SMSGatewayApi();

$SMSGatewayApi->AddGateway(new EurobateGateway('username', 'password'));

try {

    /** @var EurobateSendMessageResponse $Response */
    $Response = $SMSGatewayApi->SendMessage(
        new TextSender('Avsender'),
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

## Send SMS from number
```
<?php
use JBBx2016\SMSGateway\Common\CountryCodes;
use JBBx2016\SMSGateway\Common\PhoneNumber;
use JBBx2016\SMSGateway\Payloads\SMSPayload;
use JBBx2016\SMSGateway\Senders\TextSender;

$SMSGatewayApi->SendMessage(
    new NumberSender('203098765432'),
    new SMSPayload(
        new PhoneNumber(CountryCodes::Norway, '98765432'),
        "This is the message body"
    )
);
```

## Eurobate: Specify delivery report endpoint

```
<?php

use JBBx2016\SMSGateway\Gateways\Eurobate\EurobateGateway;
use JBBx2016\SMSGateway\SMSGatewayApi;


$EurobateGateway = new EurobateGateway('username', 'password');
$EurobateGateway->SetDeliveryReportStatusEndpoint('http:// myhost.com/dlr.php?msgid=MSGID&status=STATUS&operator=OPERATOR&smscode=OPCODE&cbgcode=CBGCODE&stop=STOP');

$SMSGatewayApi = new SMSGatewayApi();
$SMSGatewayApi->AddGateway($EurobateGateway);
```

## Exceptions

- `JBBx2016\SMSGateway\Common\Exceptions\Exception`: Base exception
  * `JBBx2016\SMSGateway\Common\Exceptions\GatewayEndpointConnectionFailedException`: Connection to gateway endpoint failed
  * `JBBx2016\SMSGateway\Common\Exceptions\GatewayAuthorizationException`: Gateway authorization failed
  * `JBBx2016\SMSGateway\Common\Exceptions\NoGatewayFoundException`: Called if no appropriate gateway is found
  * `JBBx2016\SMSGateway\Gateways\Eurobate\EurobateException`: Exception for Eurobate gateway
    * `JBBx2016\SMSGateway\Gateways\Eurobate\Exceptions\IPNotAuthorizedEurobateException`: Eurobate specific error
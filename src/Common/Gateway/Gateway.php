<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 21.02.2017
 * Time: 16.44
 */

namespace JBBx2016\SMSGateway\Common\Gateway;


use JBBx2016\SMSGateway\Common\Payload;
use JBBx2016\SMSGateway\Common\PhoneNumber;
use JBBx2016\SMSGateway\Common\Sender;
use JBBx2016\SMSGateway\Payloads\SMSPayload;

abstract class Gateway
{
    abstract public function CanProcessPhoneNumber(PhoneNumber $PhoneNumber);

    /**
     * @param Sender $Sender
     * @param Payload $Payload
     * @return GatewaySendMessageResponse
     */
    abstract public function SendMessage(Sender $Sender, Payload $Payload);
}
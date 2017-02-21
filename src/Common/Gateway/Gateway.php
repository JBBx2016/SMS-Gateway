<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 21.02.2017
 * Time: 16.44
 */

namespace JBBx2016\SMSGateway\Common\Gateway;


use JBBx2016\SMSGateway\Common\PhoneNumber;
use JBBx2016\SMSGateway\Common\Sender;
use JBBx2016\SMSGateway\Payloads\SMS;

abstract class Gateway
{
    abstract public function CanProcessPhoneNumber(PhoneNumber $PhoneNumber);

    /**
     * @param Sender $Sender
     * @param SMS $SMS
     * @return GatewaySendMessageResponse
     */
    abstract public function SendMessage(Sender $Sender, SMS $SMS);
}
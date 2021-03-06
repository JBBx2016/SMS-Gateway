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

abstract class Gateway
{
    /**
     * @param PhoneNumber $PhoneNumber
     * @return bool
     */
    abstract public function CanProcessPhoneNumber(PhoneNumber $PhoneNumber);

    /**
     * @param Sender $Sender
     * @param Payload $Payload
     * @return GatewaySendMessageResponse
     */
    abstract public function SendMessage(Sender $Sender, Payload $Payload);

    /**
     * @return string
     */
    abstract public function toString();

    /**
     * @return mixed
     */
    abstract public function getGatewayId();

    /**
     * @return string|int|null
     */
    abstract public function getAccountId();
}
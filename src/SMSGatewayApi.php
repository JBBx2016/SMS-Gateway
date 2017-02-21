<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 21.02.2017
 * Time: 16.33
 */

namespace JBBx2016\SMSGateway;


use JBBx2016\SMSGateway\Common\Exceptions\NoGatewayFoundException;
use JBBx2016\SMSGateway\Common\Gateway\Gateway;
use JBBx2016\SMSGateway\Common\Gateway\GatewaySendMessageResponse;
use JBBx2016\SMSGateway\Common\PhoneNumber;
use JBBx2016\SMSGateway\Common\Sender;
use JBBx2016\SMSGateway\Payloads\SMS;

class SMSGatewayApi
{
    /** @var Gateway[] */
    private $Gateways = array();

    public function AddGateway(Gateway $Gateway)
    {
        $this->Gateways[] = $Gateway;
    }

    /**
     * @param Sender $Sender
     * @param SMS $SMS
     * @return GatewaySendMessageResponse
     * @throws NoGatewayFoundException
     */
    public function SendMessage(Sender $Sender, SMS $SMS)
    {
        $Gateways = $this->GetGatewaysThatCanProcessSMS($SMS->GetPhoneNumber());

        if (empty($Gateways))
            throw new NoGatewayFoundException($SMS->GetPhoneNumber()->GetDebugString());

        $FirstGateway = $Gateways[0];
        return $FirstGateway->SendMessage($Sender, $SMS);
    }

    /**
     * @param PhoneNumber $PhoneNumber
     * @return Gateway[]
     */
    private function GetGatewaysThatCanProcessSMS(PhoneNumber $PhoneNumber)
    {
        $Gateways = array();

        foreach ($this->GetGateways() as $Gateway) {
            if ($Gateway->CanProcessPhoneNumber($PhoneNumber)) {
                $Gateways[] = $Gateway;
            }
        }

        return $Gateways;
    }

    /**
     * @return Gateway[]
     */
    private function GetGateways()
    {
        return $this->Gateways;
    }
}
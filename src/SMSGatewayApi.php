<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 21.02.2017
 * Time: 16.33
 */

namespace JBBx2016\SMSGateway;


use JBBx2016\SMSGateway\Common\Exceptions\Exception;
use JBBx2016\SMSGateway\Common\Exceptions\NoGatewayFoundException;
use JBBx2016\SMSGateway\Common\Gateway\Gateway;
use JBBx2016\SMSGateway\Common\Gateway\GatewaySendMessageResponse;
use JBBx2016\SMSGateway\Common\Payload;
use JBBx2016\SMSGateway\Common\PhoneNumber;
use JBBx2016\SMSGateway\Common\Sender;

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
     * @param Payload $Payload
     * @return GatewaySendMessageResponse
     * @throws Exception
     */
    public function SendMessage(Sender $Sender, Payload $Payload)
    {
        $Gateways = $this->GetGatewaysThatCanProcessSMS($Payload->GetPhoneNumber());

        if (empty($Gateways))
            throw new NoGatewayFoundException($Payload->GetPhoneNumber()->GetDebugString());

        $FirstGateway = $Gateways[0];
        return $FirstGateway->SendMessage($Sender, $Payload);
    }

    /**
     * @param PhoneNumber $PhoneNumber
     * @return Gateway[]
     */
    public function GetGatewaysThatCanProcessSMS(PhoneNumber $PhoneNumber)
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

    /**
     * @param PhoneNumber $PhoneNumber
     * @return Gateway|null
     */
    public function GetGatewayThatCanProcessSMS(PhoneNumber $PhoneNumber)
    {
        foreach ($this->GetGateways() as $Gateway) {
            if ($Gateway->CanProcessPhoneNumber($PhoneNumber)) {
                return $Gateway;
            }
        }

        return null;
    }

    /**
     * @param string $GatewayClass
     * @return Gateway
     */
    public function GetGateway($GatewayClass)
    {
        foreach ($this->Gateways as $Gateway) {
            if (get_class($Gateway) === $GatewayClass) {
                return $Gateway;
            }
        }

        return null;
    }
}
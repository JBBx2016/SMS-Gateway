<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 22.10.2017
 * Time: 09.37
 */

namespace JBBx2016\SMSGateway\Gateways\Twilio;


use JBBx2016\SMSGateway\Common\Exceptions\OnlySMSPayloadAllowedException;
use JBBx2016\SMSGateway\Common\Gateway\Gateway;
use JBBx2016\SMSGateway\Common\Gateway\GatewaySendMessageResponse;
use JBBx2016\SMSGateway\Common\Payload;
use JBBx2016\SMSGateway\Common\PhoneNumber;
use JBBx2016\SMSGateway\Common\Sender;
use JBBx2016\SMSGateway\Payloads\SMSPayload;
use Twilio\Rest\Client;

class TwilioGateway extends Gateway
{
    /** @var string */
    private $sid;

    /** @var string */
    private $token;

    /** @var  Client */
    private $client;

    public function __construct(string $sid, string $token)
    {
        $this->sid = $sid;
        $this->token = $token;
    }

    /**
     * @param PhoneNumber $PhoneNumber
     * @return bool
     */
    public function CanProcessPhoneNumber(PhoneNumber $PhoneNumber)
    {
        return true;
    }

    /**
     * @param Sender $Sender
     * @param Payload $Payload
     * @return GatewaySendMessageResponse
     * @throws OnlySMSPayloadAllowedException
     */
    public function SendMessage(Sender $Sender, Payload $Payload)
    {
        if (!($Payload instanceof SMSPayload))
            throw new OnlySMSPayloadAllowedException();

        $response = $this->getClient()->messages->create(
            $Payload->GetPhoneNumber()->getNumber(),
            [
                'from' => $Sender->GetString(),
                'body' => $Payload->GetText()
            ]
        );

        return new TwilioSendMessageResponse($response);
    }

    public function getClient(): Client
    {
        if ($this->client === null)
            $this->client = new Client($this->sid, $this->token);
        return $this->client;
    }
}
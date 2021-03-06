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
use Twilio\Exceptions\ConfigurationException;
use Twilio\Rest\Client;

class TwilioGateway extends Gateway
{
    /** @var string */
    private $sid;

    /** @var string */
    private $token;

    /** @var  Client */
    private $client;

    /** @var  bool */
    private $ignoreSender = false;

    /** @var Sender[] */
    private $senders = [];

    /** @var string|null */
    private $messagingServiceSid;


    public function __construct(string $sid, string $token)
    {
        $this->sid = $sid;
        $this->token = $token;
    }

    public function __sleep()
    {
        return ['sid', 'token', 'ignoreSender', 'senders'];
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
     * @throws ConfigurationException
     */
    public function SendMessage(Sender $Sender, Payload $Payload)
    {
        if (!($Payload instanceof SMSPayload))
            throw new OnlySMSPayloadAllowedException();

        $query = [
            'body' => $Payload->GetText()
        ];

        if ($this->messagingServiceSid) {
            $query['messagingServiceSid'] = $this->messagingServiceSid;
        } else {
            $query['from'] = $Sender->GetString();

            if ($this->ignoreSender) {
                $query['from'] = $this->senders[0]->GetString();
            }

        }

        $response = $this->getClient()->messages->create($Payload->GetPhoneNumber()->getNumber(), $query);

        return new TwilioSendMessageResponse($response);
    }

    /**
     * @return Client
     * @throws ConfigurationException
     */
    public function getClient(): Client
    {
        if ($this->client === null)
            $this->client = new Client($this->sid, $this->token);
        return $this->client;
    }

    /**
     * @param Sender[] $senders
     * @return TwilioGateway
     */
    public function setSenders(array $senders): TwilioGateway
    {
        $this->senders = $senders;
        return $this;
    }

    /**
     * @param bool $ignoreSender
     * @return TwilioGateway
     */
    public function setIgnoreSender(bool $ignoreSender): TwilioGateway
    {
        $this->ignoreSender = $ignoreSender;
        return $this;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'Twilio';
    }

    /**
     * @return string|int|null
     */
    public function getAccountId()
    {
        return $this->sid;
    }

    /**
     * @return mixed
     */
    public function getGatewayId()
    {
        return 'twilio';
    }

    /**
     * @return null|string
     */
    public function getMessagingServiceSid(): string
    {
        return $this->messagingServiceSid;
    }

    /**
     * @param null|string $messagingServiceSid
     * @return TwilioGateway
     */
    public function setMessagingServiceSid(string $messagingServiceSid): TwilioGateway
    {
        $this->messagingServiceSid = $messagingServiceSid;
        return $this;
    }
}
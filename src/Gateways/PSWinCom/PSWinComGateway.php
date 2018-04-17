<?php
/**
 * Created by PhpStorm.
 * User: Johannes Bruvik
 * Date: 05.04.2018
 * Time: 15:20
 */

namespace JBBx2016\SMSGateway\Gateways\PSWinCom;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JBBx2016\SMSGateway\Common\Exceptions\GatewayEndpointConnectionFailedException;
use JBBx2016\SMSGateway\Common\Exceptions\OnlySMSPayloadAllowedException;
use JBBx2016\SMSGateway\Common\Gateway\Gateway;
use JBBx2016\SMSGateway\Common\Payload;
use JBBx2016\SMSGateway\Common\PhoneNumber;
use JBBx2016\SMSGateway\Common\Sender;
use JBBx2016\SMSGateway\Payloads\SMSPayload;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class PSWinComGateway extends Gateway
{
    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /**
     * PSWinComGateway constructor.
     * @param string $username
     * @param string $password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param PhoneNumber $PhoneNumber
     * @return bool
     */
    public function CanProcessPhoneNumber(PhoneNumber $PhoneNumber)
    {
        try {
            return PhoneNumberUtil::getInstance()->isValidNumber($PhoneNumber->toLibPhoneNumber());
        } catch (NumberParseException $e) {
            return false;
        }
    }

    /**
     * @param Sender $Sender
     * @param Payload $Payload
     * @return void
     * @throws OnlySMSPayloadAllowedException
     * @throws GatewayEndpointConnectionFailedException
     */
    public function SendMessage(Sender $Sender, Payload $Payload)
    {
        if (!($Payload instanceof SMSPayload))
            throw new OnlySMSPayloadAllowedException($Payload);

        $guzzleClient = new Client([
            'base_uri' => 'https://simple.pswin.com',
        ]);

        try {
            $response = $guzzleClient->request(
                'GET',
                '',
                [
                    'query' => [
                        'USER' => $this->username,
                        'PW' => $this->password,
                        'RCV' => $Payload->GetPhoneNumber()->getCountryCode() . $Payload->GetPhoneNumber()->getPhoneNumber(),
                        'SND' => $Sender->GetString(),
                        'TXT' => $Payload->GetText(),
                    ]
                ]
            );
        } catch (GuzzleException $e) {
            error_log($e);
            throw new GatewayEndpointConnectionFailedException();
        }

        if ($response->getStatusCode() !== 200) {
            throw new GatewayEndpointConnectionFailedException($response->getBody());
        }
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'PSWinComGateway[username="' . $this->username . '", password="' . $this->password . '"]';
    }

    /**
     * @return string|int|null
     */
    public function getAccountId()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getGatewayId()
    {
        return 'pswincom';
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
}
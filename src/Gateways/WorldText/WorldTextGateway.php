<?php
/**
 * Created by PhpStorm.
 * User: Johannes Bruvik
 * Date: 08.03.2018
 * Time: 11:53
 */

namespace JBBx2016\SMSGateway\Gateways\WorldText;


use JBBx2016\SMSGateway\Common\Exceptions\OnlySMSPayloadAllowedException;
use JBBx2016\SMSGateway\Common\Gateway\Gateway;
use JBBx2016\SMSGateway\Common\Payload;
use JBBx2016\SMSGateway\Common\PhoneNumber;
use JBBx2016\SMSGateway\Common\Sender;
use JBBx2016\SMSGateway\Payloads\SMSPayload;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use WorldTextSms;

class WorldTextGateway extends Gateway
{

    /** @var  string */
    private $accountId;

    /** @var  string */
    private $apiKey;

    /**
     * EurobateGateway constructor.
     * @param string $accountId
     * @param string $apiKey
     */
    public function __construct($accountId, $apiKey)
    {
        $this->accountId = $accountId;
        $this->apiKey = $apiKey;
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
     * @return WorldTextSendMessageResponse
     * @throws OnlySMSPayloadAllowedException
     * @throws \wtException
     * @throws NumberParseException
     */
    public function SendMessage(Sender $Sender, Payload $Payload)
    {
        if (!($Payload instanceof SMSPayload))
            throw new OnlySMSPayloadAllowedException($Payload);

        require_once __DIR__ . '/../../../lib/WorldText/WorldText.php';

        $sms = WorldTextSms::CreateSmsInstance($this->accountId, $this->apiKey);

        $info = $sms->send(
            PhoneNumberUtil::getInstance()->format($Payload->GetPhoneNumber()->toLibPhoneNumber(), PhoneNumberFormat::E164),
            $Payload->GetText());

        return new WorldTextSendMessageResponse($info);
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'WorldText';
    }

    /**
     * @return string|int|null
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @return mixed
     */
    public function getGatewayId()
    {
        return 'worldtext';
    }
}
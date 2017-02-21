<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 21.02.2017
 * Time: 16.43
 */

namespace JBBx2016\SMSGateway\Gateways\Eurobate;

use JBBx2016\SMSGateway\Common\CountryCodes;
use JBBx2016\SMSGateway\Common\Exceptions\OnlySMSPayloadAllowedException;
use JBBx2016\SMSGateway\Common\Gateway\Gateway;
use JBBx2016\SMSGateway\Common\Gateway\GatewaySendMessageResponse;
use JBBx2016\SMSGateway\Common\Payload;
use JBBx2016\SMSGateway\Common\PhoneNumber;
use JBBx2016\SMSGateway\Common\PhoneNumberValidator\CountryCodeCondition;
use JBBx2016\SMSGateway\Common\PhoneNumberValidator\PhoneNumberGatewayValidator;
use JBBx2016\SMSGateway\Common\PhoneNumberValidator\PhoneNumberLengthCondition;
use JBBx2016\SMSGateway\Common\PhoneNumberValidator\PhoneNumberStartsWithCondition;
use JBBx2016\SMSGateway\Common\Sender;
use JBBx2016\SMSGateway\Payloads\SMSPayload;

class EurobateGateway extends Gateway
{
    /** @var  string */
    private $UserName;

    /** @var  string */
    private $Password;

    public function __construct($UserName, $Password)
    {
        $this->UserName = $UserName;
        $this->Password = $Password;
    }

    public function CanProcessPhoneNumber(PhoneNumber $PhoneNumber)
    {
        return PhoneNumberGatewayValidator::Validate($PhoneNumber, array(

            new CountryCodeCondition(array(CountryCodes::Norway)),
            new PhoneNumberLengthCondition(8),
            new PhoneNumberStartsWithCondition('9')

        ));
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
            throw new OnlySMSPayloadAllowedException($Payload);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://cpa.eurobate.com/push2.php');
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array(
            'bruker' => $this->UserName,
            'passord' => $this->Password,
            'avsender' => iconv("UTF-8", "ISO-8859-1", $Sender->GetString()),
            'til' => $Payload->GetPhoneNumber()->PhoneNumber,
            'melding' => iconv("UTF-8", "ISO-8859-1", $Payload->GetText()),
            'batch' => 0,
            'land' => 47
        ));

        $result = curl_exec($curl);
        curl_close($curl);

        return new EurobateSendMessageResponse($result);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 21.02.2017
 * Time: 16.43
 */

namespace JBBx2016\SMSGateway\Gateways\Eurobate;

use JBBx2016\SMSGateway\Common\Exceptions\GatewayAuthorizationException;
use JBBx2016\SMSGateway\Common\Exceptions\GatewayEndpointConnectionFailedException;
use JBBx2016\SMSGateway\Common\Exceptions\OnlySMSPayloadAllowedException;
use JBBx2016\SMSGateway\Common\Gateway\Gateway;
use JBBx2016\SMSGateway\Common\Gateway\GatewaySendMessageResponse;
use JBBx2016\SMSGateway\Common\Payload;
use JBBx2016\SMSGateway\Common\PhoneNumber;
use JBBx2016\SMSGateway\Common\Sender;
use JBBx2016\SMSGateway\Gateways\Eurobate\Exceptions\IPNotAuthorizedEurobateException;
use JBBx2016\SMSGateway\Gateways\Eurobate\Extensions\DeliveryReportTrait;
use JBBx2016\SMSGateway\Payloads\SMSPayload;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class EurobateGateway extends Gateway
{
    use DeliveryReportTrait;

    /** @var  string */
    private $UserName;

    /** @var  string */
    private $Password;

    /** @var  string */
    private $DeliveryReportStatusEndpoint_URL;

    /** @var  string */
    private $DeliveryReportStatusEndpoint_Secret;

    /**
     * EurobateGateway constructor.
     * @param string $UserName
     * @param string $Password
     */
    public function __construct($UserName, $Password)
    {
        $this->UserName = $UserName;
        $this->Password = $Password;
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
     * @return GatewaySendMessageResponse
     * @throws GatewayAuthorizationException
     * @throws GatewayEndpointConnectionFailedException
     * @throws IPNotAuthorizedEurobateException
     * @throws OnlySMSPayloadAllowedException
     * @throws NumberParseException
     */
    public function SendMessage(Sender $Sender, Payload $Payload)
    {
        if (!($Payload instanceof SMSPayload))
            throw new OnlySMSPayloadAllowedException($Payload);

        $message = $Payload->GetText();

        // REPLACE NON-BREAK SPACE WITH REGULAR SPACE
        $message = preg_replace('/\xc2\xa0/', ' ', $message);

        $senderName = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $Sender->GetString());
        $message = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $message);

        $phoneNumber = $Payload->GetPhoneNumber()->toLibPhoneNumber();

        $Data = array(
            'bruker' => $this->UserName,
            'passord' => $this->Password,
            'avsender' => $senderName,
            'til' => $phoneNumber->getNationalNumber(),
            'melding' => $message,
            'batch' => 0,
            'land' => $phoneNumber->getCountryCode(),
        );

        if ($this->DeliveryReportStatusEndpoint_URL)
            $Data['dlrurl'] = $this->DeliveryReportStatusEndpoint_URL . '&Secret=' . urlencode($this->DeliveryReportStatusEndpoint_Secret);


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://cpa.eurobate.com/push2.php');
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $Data);
        $result = curl_exec($curl);


        if (curl_errno($curl) !== 0)
            throw new GatewayEndpointConnectionFailedException(curl_error($curl));

        curl_close($curl);

        if (strpos($result, "IP ikke tillatt") !== false)
            throw new IPNotAuthorizedEurobateException();

        if (strpos($result, "Feil brukernavn og/eller passord") !== false)
            throw new GatewayAuthorizationException();


        return new EurobateSendMessageResponse($result);
    }

    /**
     * @param string $URL
     * @param string $Secret
     */
    public function SetDeliveryReportStatusEndpoint($URL, $Secret)
    {
        $this->DeliveryReportStatusEndpoint_URL = $URL;
        $this->DeliveryReportStatusEndpoint_Secret = $Secret;
    }

    public function ValidateSecret($Secret)
    {
        return $this->DeliveryReportStatusEndpoint_Secret === $Secret;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'EurobateGateway[username="' . $this->UserName . '", password="' . $this->Password . '"]';
    }
}
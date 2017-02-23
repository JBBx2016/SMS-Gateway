<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 21.02.2017
 * Time: 16.43
 */

namespace JBBx2016\SMSGateway\Gateways\Eurobate;

use JBBx2016\SMSGateway\Common\CountryCodes;
use JBBx2016\SMSGateway\Common\Exceptions\GatewayAuthorizationException;
use JBBx2016\SMSGateway\Common\Exceptions\GatewayEndpointConnectionFailedException;
use JBBx2016\SMSGateway\Common\Exceptions\OnlySMSPayloadAllowedException;
use JBBx2016\SMSGateway\Common\Gateway\Gateway;
use JBBx2016\SMSGateway\Common\Gateway\GatewaySendMessageResponse;
use JBBx2016\SMSGateway\Common\Payload;
use JBBx2016\SMSGateway\Common\PhoneNumber;
use JBBx2016\SMSGateway\Common\PhoneNumberValidator\Conditions\CountryCodeCondition;
use JBBx2016\SMSGateway\Common\PhoneNumberValidator\Conditions\PhoneNumberLengthCondition;
use JBBx2016\SMSGateway\Common\PhoneNumberValidator\Conditions\PhoneNumberStartsWithCondition;
use JBBx2016\SMSGateway\Common\PhoneNumberValidator\PhoneNumberGatewayValidator;
use JBBx2016\SMSGateway\Common\Sender;
use JBBx2016\SMSGateway\Gateways\Eurobate\Exceptions\IPNotAuthorizedEurobateException;
use JBBx2016\SMSGateway\Gateways\Eurobate\Extensions\DeliveryReportTrait;
use JBBx2016\SMSGateway\Payloads\SMSPayload;

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
     * @throws GatewayAuthorizationException
     * @throws GatewayEndpointConnectionFailedException
     * @throws IPNotAuthorizedEurobateException
     * @throws OnlySMSPayloadAllowedException
     */
    public function SendMessage(Sender $Sender, Payload $Payload)
    {
        if (!($Payload instanceof SMSPayload))
            throw new OnlySMSPayloadAllowedException($Payload);

        $Data = array(
            'bruker' => $this->UserName,
            'passord' => $this->Password,
            'avsender' => iconv("UTF-8", "ISO-8859-1", $Sender->GetString()),
            'til' => $Payload->GetPhoneNumber()->PhoneNumber,
            'melding' => iconv("UTF-8", "ISO-8859-1", $Payload->GetText()),
            'batch' => 0,
            'land' => 47
        );

        if ($this->DeliveryReportStatusEndpoint_URL)
            $Data['dlrurl'] = $this->DeliveryReportStatusEndpoint_URL;


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
}
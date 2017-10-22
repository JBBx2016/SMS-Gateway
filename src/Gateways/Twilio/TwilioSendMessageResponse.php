<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 22.10.2017
 * Time: 09.44
 */

namespace JBBx2016\SMSGateway\Gateways\Twilio;


use JBBx2016\SMSGateway\Common\Gateway\GatewaySendMessageResponse;
use Twilio\Rest\Api\V2010\Account\MessageInstance;

class TwilioSendMessageResponse extends GatewaySendMessageResponse
{
    /** @var MessageInstance */
    private $response;

    /**
     * TwilioSendMessageResponse constructor.
     * @param MessageInstance $response
     */
    public function __construct(MessageInstance $response)
    {
        $this->response = $response;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 21.02.2017
 * Time: 18.13
 */

namespace JBBx2016\SMSGateway\Gateways\Eurobate;


use JBBx2016\SMSGateway\Common\Gateway\GatewaySendMessageResponse;

class EurobateSendMessageResponse extends GatewaySendMessageResponse
{
    /** @var  int */
    public $Id;

    /** @var  string */
    public $RawResponse;

    public function __construct($RawResponse)
    {
        $this->Id = (int)substr($RawResponse, strrpos($RawResponse, " ") + 1);


        $this->RawResponse = $RawResponse;
    }
}
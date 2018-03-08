<?php
/**
 * Created by PhpStorm.
 * User: Johannes Bruvik
 * Date: 08.03.2018
 * Time: 12:01
 */

namespace JBBx2016\SMSGateway\Gateways\WorldText;


class WorldTextSendMessageResponse
{
    private $info;

    /**
     * WorldTextSendMessageResponse constructor.
     * @param $info
     */
    public function __construct($info)
    {
        $this->info = $info;
    }
}
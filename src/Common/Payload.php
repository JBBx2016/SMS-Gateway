<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 21.02.2017
 * Time: 18.47
 */

namespace JBBx2016\SMSGateway\Common;


abstract class Payload
{
    /** @var PhoneNumber */
    protected $PhoneNumber;

    /**
     * @return PhoneNumber
     */
    public function GetPhoneNumber()
    {
        return $this->PhoneNumber;
    }
}
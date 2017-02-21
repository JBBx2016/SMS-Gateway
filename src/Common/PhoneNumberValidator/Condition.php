<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 21.02.2017
 * Time: 18.02
 */

namespace JBBx2016\SMSGateway\Common\PhoneNumberValidator;


use JBBx2016\SMSGateway\Common\PhoneNumber;

abstract class Condition
{
    /**
     * @param PhoneNumber $PhoneNumber
     * @return bool
     */
    abstract public function PhoneNumberMatch(PhoneNumber $PhoneNumber);
}
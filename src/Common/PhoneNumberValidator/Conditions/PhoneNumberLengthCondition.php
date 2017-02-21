<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 21.02.2017
 * Time: 18.02
 */

namespace JBBx2016\SMSGateway\Common\PhoneNumberValidator\Conditions;


use JBBx2016\SMSGateway\Common\PhoneNumber;
use JBBx2016\SMSGateway\Common\PhoneNumberValidator\Condition;

class PhoneNumberLengthCondition extends Condition
{
    /** @var  int */
    public $Length;

    public function __construct($Length)
    {
        $this->Length = $Length;
    }

    /**
     * @param PhoneNumber $PhoneNumber
     * @return bool
     */
    public function PhoneNumberMatch(PhoneNumber $PhoneNumber)
    {
        return strlen($PhoneNumber->PhoneNumber) === $this->Length;
    }
}
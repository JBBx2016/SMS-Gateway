<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 21.02.2017
 * Time: 17.58
 */

namespace JBBx2016\SMSGateway\Common\PhoneNumberValidator\Conditions;


use JBBx2016\SMSGateway\Common\PhoneNumber;
use JBBx2016\SMSGateway\Common\PhoneNumberValidator\Condition;

class PhoneNumberStartsWithCondition extends Condition
{
    /** @var string */
    public $StartsWith;

    public function __construct($StartsWith)
    {
        $this->StartsWith = $StartsWith;
    }

    /**
     * @param PhoneNumber $PhoneNumber
     * @return bool
     */
    public function PhoneNumberMatch(PhoneNumber $PhoneNumber)
    {
        return substr($PhoneNumber->PhoneNumber, 0, strlen($this->StartsWith)) === $this->StartsWith;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 21.02.2017
 * Time: 17.53
 */

namespace JBBx2016\SMSGateway\Common\PhoneNumberValidator;


use JBBx2016\SMSGateway\Common\PhoneNumber;

class PhoneNumberGatewayValidator
{
    /**
     * @param PhoneNumber $PhoneNumber
     * @param Condition[] $Conditions
     * @return bool
     */
    public static function Validate(PhoneNumber $PhoneNumber, $Conditions)
    {
        foreach ($Conditions as $Condition) {
            if ($Condition->PhoneNumberMatch($PhoneNumber) === false) {
                return false;
            }
        }
        return true;
    }
}
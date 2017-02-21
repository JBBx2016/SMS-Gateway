<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 21.02.2017
 * Time: 18.06
 */

namespace JBBx2016\SMSGateway\Common\PhoneNumberValidator;


use JBBx2016\SMSGateway\Common\PhoneNumber;

class CountryCodeCondition extends Condition
{
    /** @var  array */
    private $CountryCodes;

    /**
     * CountryCodeCondition constructor.
     * @param string[] $CountryCodes
     */
    public function __construct($CountryCodes)
    {
        $this->CountryCodes = $CountryCodes;
    }

    /**
     * @param PhoneNumber $PhoneNumber
     * @return bool
     */
    public function PhoneNumberMatch(PhoneNumber $PhoneNumber)
    {
        return in_array($PhoneNumber->CountryCode, $this->CountryCodes, true);
    }
}
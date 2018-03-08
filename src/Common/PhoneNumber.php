<?php


/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 21.02.2017
 * Time: 16.36
 */

namespace JBBx2016\SMSGateway\Common;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class PhoneNumber
{
    /** @var  string */
    public $CountryCode;

    /** @var  string */
    public $PhoneNumber;

    public function __construct($CountryCode, $PhoneNumber)
    {
        $this->CountryCode = $CountryCode;
        $this->PhoneNumber = $PhoneNumber;
    }

    public function GetDebugString()
    {
        return "CountryCode='{$this->CountryCode}', PhoneNumber='{$this->PhoneNumber}'";
    }

    public function getNumber()
    {
        return '+' . $this->getCountryCode() . $this->getPhoneNumber();
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->CountryCode;
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->PhoneNumber;
    }

    /**
     * @return \libphonenumber\PhoneNumber
     * @throws NumberParseException
     */
    public function toLibPhoneNumber()
    {
        return PhoneNumberUtil::getInstance()->parse('+' . $this->getCountryCode() . $this->getNumber());
    }
}
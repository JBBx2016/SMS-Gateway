<?php


/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 21.02.2017
 * Time: 16.36
 */

namespace JBBx2016\SMSGateway\Common;

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
    public function getCountryCode(): string
    {
        return $this->CountryCode;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->PhoneNumber;
    }

}
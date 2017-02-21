<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 21.02.2017
 * Time: 16.36
 */

namespace JBBx2016\SMSGateway\Payloads;

use JBBx2016\SMSGateway\Common\PhoneNumber;

class SMS
{
    /** @var PhoneNumber */
    protected $PhoneNumber;

    /** @var  string */
    protected $Text;

    public function __construct(PhoneNumber $phoneNumber, $Text)
    {
        $this->PhoneNumber = $phoneNumber;
        $this->Text = $Text;
    }

    /**
     * @return PhoneNumber
     */
    public function GetPhoneNumber()
    {
        return $this->PhoneNumber;
    }

    /**
     * @return string
     */
    public function GetText()
    {
        return $this->Text;
    }
}
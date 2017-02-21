<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 21.02.2017
 * Time: 18.22
 */

namespace JBBx2016\SMSGateway\Senders;


use JBBx2016\SMSGateway\Common\Sender;

class TextSender extends Sender
{
    /** @var  string */
    protected $Text;

    public function __construct($Text)
    {
        $this->Text = $Text;
    }

    /**
     * @return string
     */
    public function GetString()
    {
        return $this->Text;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 21.02.2017
 * Time: 18.22
 */

namespace JBBx2016\SMSGateway\Senders;


use JBBx2016\SMSGateway\Common\Sender;

class NumberSender extends Sender
{
    /** @var  string */
    protected $Number;

    public function __construct($Number)
    {
        $this->Number = $Number;
    }

    /**
     * @return string
     */
    public function GetString()
    {
        return $this->Number;
    }
}
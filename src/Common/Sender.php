<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 21.02.2017
 * Time: 18.23
 */

namespace JBBx2016\SMSGateway\Common;


abstract class Sender
{
    /**
     * @return string
     */
    abstract public function GetString();
}
<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 23.02.2017
 * Time: 10.18
 */

namespace JBBx2016\SMSGateway\DeliveryReport;


use JBBx2016\SMSGateway\Common\Country;

class OperatorStatus
{
    /** @var  Country */
    private $CountryClass;

    /** @var  string */
    private $Operator;

    /** @var  string */
    private $OperatorStatusCode;

    public function __construct($CountryClass, $Operator, $OperatorStatusCode)
    {
        $this->CountryClass = $CountryClass;
        $this->Operator = $Operator;
        $this->OperatorStatusCode = $OperatorStatusCode;
    }
}
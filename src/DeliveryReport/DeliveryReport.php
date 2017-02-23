<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 23.02.2017
 * Time: 10.01
 */

namespace JBBx2016\SMSGateway\DeliveryReport;

class DeliveryReport
{
    /** @var OperatorStatus */
    private $OperatorStatus;

    /** @var  string */
    private $Status;

    public function __construct(OperatorStatus $OperatorStatus, $Status)
    {
        $this->OperatorStatus = $OperatorStatus;
        $this->Status = $Status;
    }

    /**
     * @see \JBBx2016\SMSGateway\DeliveryReport\Status
     * @return string
     */
    public function GetStatus()
    {
        return $this->Status;
    }
}
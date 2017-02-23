<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 23.02.2017
 * Time: 10.02
 */

namespace JBBx2016\SMSGateway\Gateways\Eurobate\Extensions;

use JBBx2016\SMSGateway\Common\Countries\Norway;
use JBBx2016\SMSGateway\DeliveryReport\DeliveryReport;
use JBBx2016\SMSGateway\DeliveryReport\OperatorStatus;
use JBBx2016\SMSGateway\DeliveryReport\Status;
use JBBx2016\SMSGateway\Gateways\Eurobate\Exceptions\UnknownStatusDeliveryReportException;


trait DeliveryReportTrait
{
    public static /** @noinspection SpellCheckingInspection */
        $StatusTranslation = array(

        'sendt' => Status::Sent,
        'billed' => Status::Billed,
        'acked' => Status::OperatorAcknowledged,
        'rejected' => Status::OperatorRejected,
        'buffered' => Status::Buffered,
        'delivered' => Status::Delivered,
        'failed' => Status::Failed

    );

    public function ParseDeliveryReport($Status, $Operator, $OperatorStatusCode)
    {
        if (!array_key_exists($Status, self::$StatusTranslation))
            throw new UnknownStatusDeliveryReportException($Status);

        $DeliveryReport_Status = self::$StatusTranslation[$Status];

        return new DeliveryReport(
            new OperatorStatus(
                Norway::class,
                $Operator,
                $OperatorStatusCode
            ),
            $DeliveryReport_Status
        );
    }
}
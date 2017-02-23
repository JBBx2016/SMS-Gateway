<?php
/**
 * Created by PhpStorm.
 * User: JBB
 * Date: 23.02.2017
 * Time: 10.04
 */

namespace JBBx2016\SMSGateway\DeliveryReport;


class Status
{
    /**
     * Message sent to the SMS gateway and is waiting to be picked up.
     */
    const Sent = 'Sent';

    /**
     * Successful billing has been performed by the Tele2/Telia billing gateway. Message not yet delivered to handset.
     */
    const Billed = 'Billed';

    /**
     * Message picked up and accepted by operator’s SMSC. Awaiting final delivered/failed status.
     */
    const Acknowledged = 'Acknowledged';

    /**
     * Message sendt from Eurobate and was but was rejected by operator’s SMSC. Usually due to wrong phonenumber format or missing information.
     */
    const Rejected = 'Rejected';

    /**
     * Message was tried sendt to the phone but could not be delivered, usually because the phone was turned off. OperatorStatus will retry until timeout, whereas the final status will be set to delivered or failed.
     */
    const Buffered = 'Buffered';

    /**
     * Message delivered to the phone.
     */
    const Delivered = 'Delivered';

    /**
     * Message not delivered to phone. Usually because the phone has been turned off and the retry period has expired, or the message is sendt to a invalid phone-number, or billing could not be completed.
     */
    const Failed = 'Failed';
}
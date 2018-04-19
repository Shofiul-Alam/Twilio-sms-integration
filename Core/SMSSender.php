<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Author Md Shofiul Alam
 * Copyright (c) 2011-present Toolmates Hire. All rights reserved.
 * See https://toolmateshire.com.au for license details.
 */

namespace XLite\Module\Shofi\TwilioSMS\Core;

/**
 * SMSSender core class
 */
class SMSSender extends \XLite\Base\Singleton
{
    public static function sendSMS($sms, $vendorMobileNo) {

        $config = new \XLite\Module\Shofi\TwilioSMS\TwilioConfig();


        $client = new \Twilio\Rest\Client($config->getTwilioSID(), $config->getTwilioToken());


        $client->messages
            ->create(
                $vendorMobileNo,
                array(
                    "from" => $config->getTwilioalphaNumericID(),
                    "body" => $sms,
                )
            );

    }
}

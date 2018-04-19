<?php

namespace  XLite\Module\Shofi\TwilioSMS;

/**
 * Author Md Shofiul Alam
 * Copyright (c) 2011-present Toolmates Hire. All rights reserved.
 * See https://toolmateshire.com.au for license details.
 */

class TwilioConfig extends \XLite\Base\Singleton {

    private $twilioSID;
    private $twilioToken;
    private $twilioMobile;
    private $twilioAlphaNumericID;

    /**
     * @return mixed
     */
    public function getTwilioSID()
    {
        $config = \XLite\Core\Config::getInstance();
        $key = $config->Shofi->TwilioSMS->twilioSID;
        return $this->twilioSID = $key;
    }

    /**
     * @return mixed
     */
    public function getTwilioToken()
    {
        $config = \XLite\Core\Config::getInstance();
        $key = $config->Shofi->TwilioSMS->twilioToken;
        return $this->twilioToken = $key;
    }


    /**
     * @return mixed
     */
    public function getTwilioMobile()
    {
        $config = \XLite\Core\Config::getInstance();
        $secret = $config->Shofi->TwilioSMS->twilioMobile;
        return $this->twilioMobile = $secret;
    }
    /**
     * @return mixed
     */
    public function getTwilioalphaNumericID()
    {
        $config = \XLite\Core\Config::getInstance();
        $secret = $config->Shofi->TwilioSMS->twilioAlphaNumericID;
        return $this->twilioAlphaNumericID = $secret;
    }


}

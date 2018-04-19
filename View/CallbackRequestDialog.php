<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Author Md Shofiul Alam
 * Copyright (c) 2011-present Toolmates Hire. All rights reserved.
 * See https://toolmateshire.com.au for license details.
 */


namespace XLite\Module\Shofi\TwilioSMS\View;

/**
 * Modify review widget
 *
 */
class CallbackRequestDialog extends \XLite\View\SimpleDialog
{

    /**
     * Return list of allowed targets
     *
     * @return array
     */
    public static function getAllowedTargets()
    {
        $list = parent::getAllowedTargets();
        $list[] = 'callback_request';

        return $list;
    }

    /**
     * Return file name for the center part template
     *
     * @return string
     */
    protected function getBody()
    {
        return 'modules/Shofi/TwilioSMS/callback_request/body.twig';
    }
}

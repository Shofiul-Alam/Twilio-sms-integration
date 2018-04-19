<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Author Md Shofiul Alam
 * Copyright (c) 2011-present Toolmates Hire. All rights reserved.
 * See https://toolmateshire.com.au for license details.
 */


namespace XLite\Module\Shofi\TwilioSMS\View\Form\CallbackRequest\Customer;

/**
 * Add/edit review form
 */
class CallbackRequest extends \XLite\View\Form\AForm
{
    /**
     * Widget params names
     */
    const PARAM_ID              = 'id';
    const PARAM_PRODUCT_ID      = 'product_id';

    /**
     * Return default value for the "target" parameter
     *
     * @return string
     */
    protected function getDefaultTarget()
    {
        return 'callback_request';
    }

    /**
     * Return default value for the "action" parameter
     *
     * @return string
     */
    protected function getDefaultAction()
    {
        return 'create';
    }

    /**
     * Return list of the form default parameters
     *
     * @return array
     */
    protected function getDefaultParams()
    {
        $params = array(
            self::PARAM_ID              => \XLite\Core\Request::getInstance()->id,
            self::PARAM_PRODUCT_ID      => \XLite\Core\Request::getInstance()->product_id,
        );

        return $params;
    }
}

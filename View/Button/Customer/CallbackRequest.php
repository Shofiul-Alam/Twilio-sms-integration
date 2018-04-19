<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Author Md Shofiul Alam
 * Copyright (c) 2011-present Toolmates Hire. All rights reserved.
 * See https://toolmateshire.com.au for license details.
 */

namespace XLite\Module\Shofi\TwilioSMS\View\Button\Customer;

/**
 * Add review button widget
 *
 */
class CallbackRequest extends \XLite\View\Button\APopupButton
{
    /*
     * Widget param names
     */
    const PARAM_PRODUCT = 'product';

    /**
     * Get JS files
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = 'modules/Shofi/TwilioSMS/button/js/callback_request.js';

        return $list;
    }

    /**
     * Register CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = 'modules/Shofi/TwilioSMS/callback_request/callback_request.css';

        return $list;
    }

    /**
     * Register files from common repository
     *
     * @return array
     */
    public function getCommonFiles()
    {
        $list = parent::getCommonFiles();
        $list[static::RESOURCE_JS][] = 'js/tooltip.js';

        return $list;
    }

    /**
     * Define widget params
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_PRODUCT => new \XLite\Model\WidgetParam\TypeObject('Product', null, false, '\XLite\Model\Product'),
        );
    }

    /**
     * Get product
     *
     * @return \XLite\Model\Product
     */
    protected function getProduct()
    {
        return $this->getParam(self::PARAM_PRODUCT);
    }

    /**
     * Get productid
     *
     * @return integer
     */
    protected function getProductId()
    {
        return $this->getProduct()
            ? $this->getProduct()->getProductId()
            : null;
    }

    /**
     * Return URL parameters to use in AJAX popup
     *
     * @return array
     */
    protected function prepareURLParams()
    {
        return array(
            'target'        => 'callback_request',
            'product_id'    => $this->getProductId(),
            'return_target' => \XLite\Core\Request::getInstance()->target,
            'widget'        => '\XLite\Module\Shofi\TwilioSMS\View\CallbackRequestDialog',
        );
    }

    /**
     * Return CSS class
     *
     * @return string
     */
    protected function getClass()
    {
        return parent::getClass() . ' regular-main-button callback-request ';
    }
}

<?php
// vim: set ts=4 sw=4 sts=4 et:



namespace XLite\Module\Shofi\TwilioSMS\Model;

/**
 * Product
 *
 */
class Product extends \XLite\Model\Product implements \XLite\Base\IDecorator
{
    /**
     * Product questions
     *
     * @var \Doctrine\Common\Collections\Collection
     *
     * @OneToMany (targetEntity="XLite\Module\Shofi\TwilioSMS\Model\CallbackRequest", mappedBy="product", cascade={"all"})
     * @OrderBy   ({"date" = "DESC"})
     */
    protected $callbackRequest;

    /**
     * Adds a question about the product.
     *
     * @param XLite\Module\Shofi\TwilioSMS\Model\CallbackRequest
     *
     * @return Product
     */
    public function addCallbackRequest(\XLite\Module\Shofi\TwilioSMS\Model\CallbackRequest $callbackRequest)
    {
        $this->callbackRequest[] = $callbackRequest;

        return $this;
    }

    /**
     * Returns questions about the product.
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCallbackRequest()
    {
        return $this->callbackRequest;
    }
}

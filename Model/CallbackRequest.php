<?php

namespace XLite\Module\Shofi\TwilioSMS\Model;
/**
 * @Entity
 * @Table (name="callback_request")
 */


class CallbackRequest extends \XLite\Model\AEntity {

    /**
     * @Id
     * @GeneratedValue (strategy = "AUTO")
     * @column (type="integer")
     *
     */
    protected $id;

    /**
     * Name of the customer asked the question.
     *
     * @var string
     *
     * @Column (type="string")
     */
    protected $name = '';

    /**
     * Mobile of the customer asked the question.
     *
     * @var string
     *
     * @Column (type="string")
     */
    protected $mobile = '';




    /**
     * Relation to a product entity
     *
     * @var \XLite\Model\Product
     *
     * @ManyToOne  (targetEntity="XLite\Model\Product", inversedBy="callbackRequest")
     * @JoinColumn (name="product_id", referencedColumnName="product_id")
     */

    protected $product;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * @return \XLite\Model\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param \XLite\Model\Product $product
     */
    public function setProduct(\XLite\Model\Product $product)
    {
        $this->product = $product;
    }


}

?>
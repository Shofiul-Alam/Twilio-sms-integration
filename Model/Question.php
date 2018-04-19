<?php
namespace XLite\Module\Shofi\TwilioSMS\Model;



class Question extends \XLite\Module\QSL\ProductQuestions\Model\Question implements \XLite\Base\IDecorator {

    /**
     * Mobile of the customer asked the question.
     *
     * @var string
     *
     * @Column (type="string")
     */
    protected $mobile = '';

    /**
     * @return string
     */
    public function getMobile(): string
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     */
    public function setMobile(string $mobile)
    {
        $this->mobile = $mobile;
    }




}
?>
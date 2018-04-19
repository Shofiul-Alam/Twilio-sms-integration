<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Author Md Shofiul Alam
 * Copyright (c) 2011-present Toolmates Hire. All rights reserved.
 * See https://toolmateshire.com.au for license details.
 */


namespace XLite\Module\Shofi\TwilioSMS\Controller\Admin;


/**
 * Question controller
 */
class ProductQuestion extends \XLite\Module\QSL\ProductQuestions\Controller\Admin\ProductQuestion implements \XLite\Base\IDecorator
{

    /**
     * Do necessary actions when the question has been answered the first time.
     *
     * @param \XLite\Module\QSL\ProductQuestions\Model\Question $model Question model
     *
     * @return void
     */
    protected function doFirstAnswerActions(\XLite\Module\QSL\ProductQuestions\Model\Question $model)
    {
        if ($this->isCustomerNotificationEnabled()) {
            $this->sendAnswerToCustomer($model);
        }
    }




    /**
     * Email answer to the customer that asked the question.
     *
     * @param \XLite\Module\QSL\ProductQuestions\Model\Question $question Question model
     *
     *@return void
     */
    protected function sendAnswerToCustomer($question)
    {

        $answer = $question->getAnswer();
        $customerMobile = $question->getMobile();
        if(substr($customerMobile, 0, 1) == 0) {
            $customerMobile = "+61".substr($customerMobile, 1);
        }
        $product = $question->getProduct();
        $productName = '';
        $mobileNo = '';

        if($product) {
            $vendor = $product->getVendor();
            $productName = $product->getName();
            $address = $vendor->getFirstAddress();
            $addressFields = $address->getAddressFields();

            foreach($addressFields as $field) {
                $fieldName = $field->getAddressField()->getServiceName();
                if($fieldName === 'phone' && $field->getValue() != null && !empty($field->getValue())) {
                    $mobileNo = $field->getValue();
                }

            }
        }


        $sms = $productName.': "'.$answer.'" - '. $vendor->getName() . ', Call me on '.  $mobileNo.' for further discussion.';




        if($customerMobile != null && !empty($customerMobile) && $answer != null) {
            $this->sendSMS($sms, $customerMobile);
        }

        \XLite\Core\Mailer::getInstance()->sendProductQuestionAnswerCustomer($question);


    }

    protected function sendSMS($sms, $customerMobile) {

        $config = new \XLite\Module\Shofi\TwilioSMS\TwilioConfig();


        $client = new \Twilio\Rest\Client($config->getTwilioSID(), $config->getTwilioToken());


        $client->messages
            ->create(
                $customerMobile,
                array(
                    "from" => $config->getTwilioalphaNumericID(),
                    "body" => $sms,
                )
            );
    }


}
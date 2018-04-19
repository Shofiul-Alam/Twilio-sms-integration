<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Author Md Shofiul Alam
 * Copyright (c) 2011-present Toolmates Hire. All rights reserved.
 * See https://toolmateshire.com.au for license details.
 */

namespace XLite\Module\Shofi\TwilioSMS\Controller\Customer;

use XLite\Module\Shofi\TwilioSMS\TwilioConfig;

/**
 * NewsletterSubscriptions controller
 */
class ProductQuestion extends \XLite\Module\QSL\ProductQuestions\Controller\Customer\ProductQuestion implements \XLite\Base\IDecorator
{
    /**
     * Create new model
     *
     * @return void
     */
    protected function doActionCreate()
    {

        $data = $this->filterRequestData(\XLite\Core\Request::getInstance()->getData());

        if ($this->isAllowedAskQuestion() && $this->validateQuestion($data)) {
            $question = $this->createQuestion($data);

            $product_id = \XLite\Core\Request::getInstance()->product_id;
            $productName = '';
            $vendor = false;
            if($product_id) {
                $product = \XLite\Core\Database::getRepo('\XLite\Model\Product')->find($product_id);
                $productName = $product->getName();

                $vendor = $product->getVendor();
            }

            $vendorMobileNo = '+61424460522';
            $mobileNo = '';

            if($vendor) {

                $address = $vendor->getFirstAddress();
                $addressFields = $address->getAddressFields();

                foreach($addressFields as $field) {
                    $fieldName = $field->getAddressField()->getServiceName();
                    if($fieldName === 'phone' && $field->getValue() != null && !empty($field->getValue())) {
                        $mobileNo = $field->getValue();
                    }

                }
            }
            if(substr($mobileNo, 0, 1) == 0) {
                $vendorMobileNo = "+61".substr($mobileNo, 1);
            }
            $name = explode(" ", $data['name']);
            $questionBy = strtoupper($name[0]);

            $sms = 'Question received from '.$questionBy.' regarding your '. $productName.'. Please Login to reply - '.'https://toolmateshire.com.au/admin.php';


            $this->sendSMS($sms, $vendorMobileNo);



            $this->closeQuestionPopup($question);


        } else {

            $this->processInvalidQuestion($data);

        }
    }

    protected function sendSMS($sms, $vendorMobileNo) {

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

    /**
     * Do necessary actions for a question that has passed the validation.
     *
     * @param array $data Submitted question fields
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     *
     * @return void
     */
    protected function createQuestion(array $data)
    {
        $question = new \XLite\Module\QSL\ProductQuestions\Model\Question;
        $question->setName($data['name']);
        $question->setEmail($data['email']);
        $question->setMobile($data['mobile']);
        $question->setQuestion(isset($data['question']) ? trim($data['question']) : '');
        $question->setPrivate(isset($data['private']) ? intval($data['private']) : 0);
        $question->setProfile($this->getProfile());
        $product = \XLite\Core\Database::getRepo('XLite\Model\Product')->find($this->getProductId());
        $question->setProduct($product);
        $product->addQuestions($question);
        $question->create();
        $this->updateQuestionIds($question);

        \XLite\Core\Mailer::getInstance()->sendNewProductQuestionAdmin($question);

        \XLite\Core\TopMessage::addInfo(
            static::t('Thank your for asking the question!')
        );
    }

    /**
     * Filters the request data and returns only those parameters that relate to product questions.
     *
     * @param array $data Request data
     *
     * @return array
     */
    protected function filterRequestData(array $data)
    {
        $allowed = [
            'id'         => 'id',
            'product_id' => 'product_id',
            'name'       => 'name',
            'email'      => 'email',
            'mobile'      => 'mobile',
            'question'   => 'question',
            'private'    => 'private',
        ];

        return array_intersect_key($data, $allowed);
    }

}

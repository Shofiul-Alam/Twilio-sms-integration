<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Author Md Shofiul Alam
 * Copyright (c) 2011-present Toolmates Hire. All rights reserved.
 * See https://toolmateshire.com.au for license details.
 */

namespace XLite\Module\Shofi\TwilioSMS\Controller\Customer;


/**
 * "Ask question" controller.
 *
 */
class CallbackRequest extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Question
     *
     * @var \XLite\Module\Shofi\TwilioSMS\Controller\Customer\CallbackRequest
     */
    protected $callbackRequest;

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     */
    public function getTitle()
    {
        return 'Request for Callback';
    }

    /**
     * Get return URL
     *
     * @return string
     */
    public function getReturnURL()
    {
        // Reload the page if the form is submitted
        return \XLite\Core\Request::getInstance()->action ? '' : parent::getReturnURL();
    }

    /**
     * Return current product Id
     *
     * @param boolean $getFromQuestion If the product ID should be taken from the current question OPTIONAl
     *
     * @return integer
     */
    public function getProductId($getFromQuestion = true)
    {
        $productId = \XLite\Core\Request::getInstance()->product_id;

        if (empty($productId) && $getFromQuestion) {
            $question = $this->getQuestion();
            if ($question) {
                $productId = $question->getProduct()->getProductId();
            }
        }

        return $productId;
    }

    /**
     * Return question
     *
     * @return \XLite\Module\QSL\ProductQuestions\Model\Question
     */
    public function getCallbackRequest()
    {
        $id = $this->getId();

        if ($id) {
            $callbackRequest = \XLite\Core\Database::getRepo('XLite\Module\Shofi\TwilioSMS\Model\CallbackRequest')->find($id);
        } else {
            $callbackRequest = new \XLite\Module\Shofi\TwilioSMS\Model\CallbackRequest();
            $callbackRequest->setName($this->getProfileField('name'));
        }


        return $callbackRequest;
    }

    /**
     * Return question Id
     *
     * @return integer
     */
    public function getId()
    {
        return null; // \XLite\Core\Request::getInstance()->id;
    }

    /**
     * Return current category Id
     *
     * @return integer
     */
    public function getCategoryId()
    {
        return \XLite\Core\Request::getInstance()->category_id;
    }

    /**
     * Return current profile
     *
     * @return \XLite\Model\Profile
     */
    public function getProfile()
    {
        return \XLite\Core\Auth::getInstance()->getProfile() ? : null;
    }

    /**
     * Return field value from current profile
     *
     * @param string $field Field name
     *
     * @return string
     */
    public function getProfileField($field)
    {
        $value = '';
        $auth = \XLite\Core\Auth::getInstance();
        if ($auth->isLogged()) {
            switch ($field) {
                case 'name':
                    if (0 < $auth->getProfile()->getAddresses()->count()) {
                        $value = $auth->getProfile()->getAddresses()->first()->getName();
                    }
                    break;

                case 'email':
                    $value = $auth->getProfile()->getLogin();
                    break;

                default:
            }
        }

        return $value;
    }

    /**
     * Alias
     *
     * @return \XLite\Module\QSL\ProductQuestions\Model\Question
     */
    protected function getEntity()
    {
        return $this->getQuestion();
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
            'mobile'      => 'email',
        ];
        
        return array_intersect_key($data, $allowed);
    }

    /**
     * Update review ids saved in session
     * used for connection between anonymous user and his reviews
     *
     * @param \XLite\Module\QSL\ProductQuestions\Model\Question $entity Question model
     *
     * @return boolean
     */
    protected function updateQuestionIds(\XLite\Module\QSL\ProductQuestions\Model\Question $entity)
    {
        if (!$this->getProfile()) {

            $questionIds = \XLite\Core\Session::getInstance()->questionIds;

            if (!is_array($questionIds)) {
                $questionIds = array();
            }

            if ($entity->getId()) {
                array_push($questionIds, $entity->getId());
            }

            \XLite\Core\Session::getInstance()->questionIds = array_unique($questionIds);
        }

        return true;
    }

    /**
     * Modify model
     *
     * @return void
     */
    protected function doActionModify()
    {
        $this->doActionCreate();
    }

    /**
     * Create new model
     *
     * @return void
     */
    protected function doActionCreate()
    {
        $data = $this->filterRequestData(\XLite\Core\Request::getInstance()->getData());


        $product_id = \XLite\Core\Request::getInstance()->product_id;
        $productName = '';
        $vendor = false;

        if($product_id) {
            $product = \XLite\Core\Database::getRepo('\XLite\Model\Product')->find($product_id);
            $productName = $product->getName();
            $vendor = $product->getVendor()->getVendor()->getProfile();
        }


        $customerMobileNo = $data['mobile'];
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
        $requestBy = strtoupper($name[0]);

        $sms = $requestBy.'is requesting a call back regarding your '.$productName.' on ToolMates Hire. Please call him back on '. $customerMobileNo.' to discuss further';

        If($mobileNo != null && !empty($mobileNo) && $mobileNo != '') {
            \XLite\Module\Shofi\TwilioSMS\Core\SMSSender::getInstance()->sendSMS($sms, $vendorMobileNo);
        }



        $callbackRequest = $this->createCallbackRequest($data);
        $this->closeQuestionPopup($callbackRequest);

    }

    /**
     * Do necessary actions if the submitted question has failed to pass the validation.
     *
     * @param array $data Submitted question fields
     *
     * @return void
     */
    protected function processInvalidQuestion(array $data)
    {
        $this->set('valid', false);
        $this->setReturnURL($this->buildURL('product_question'));
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
    protected function createCallbackRequest(array $data)
    {
        $callbackRequest = new \XLite\Module\Shofi\TwilioSMS\Model\CallbackRequest();
        $callbackRequest->setName($data['name']);
        $callbackRequest->setMobile($data['mobile']);
        $product = \XLite\Core\Database::getRepo('XLite\Model\Product')->find($this->getProductId());

        $callbackRequest->setProduct($product);
        $product->addCallbackRequest($callbackRequest);
        $callbackRequest->create();

        \XLite\Core\TopMessage::addInfo(
            static::t('A SMS send to our vendor\'s Mobile. He will reply back to you soon!')
        );
//        \XLite\Core\Mailer::getInstance()->sendNewCallbackRequestAdmin($callbackRequest);


    }

    /**
     * Closes the question popup.
     *
     * @param \XLite\Module\QSL\ProductQuestions\Model\Question $question Question model
     *
     * @return boolean
     */
    protected function closeQuestionPopup($callbackRequest)
    {
        $this->setSilenceClose();
    }

    /**
     * Validates the submitted data.
     *
     * @param array $data Submitted question fields
     *
     * @return boolean
     */
    protected function validateQuestion($data)
    {
        $errors = array();

        if (!isset($data['name']) || !$data['name']) {
            $errors['name'] = 'Please enter your name';
        }

        if (!isset($data['question']) || !$data['question']) {
            $errors['question'] = 'Please enter the question';
        }

        foreach ($errors as $field => $message) {
            if (!$this->isAJAX()) {
                \XLite\Core\TopMessage::addError($message);
            }
            \XLite\Core\Event::invalidElement($field, static::t($message));
        }

        return empty($errors);
    }

}

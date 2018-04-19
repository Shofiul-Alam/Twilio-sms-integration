<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Author Md Shofiul Alam
 * Copyright (c) 2011-present Toolmates Hire. All rights reserved.
 * See https://toolmateshire.com.au for license details.
 */

namespace XLite\Module\Shofi\TwilioSMS\View\Model;

/**
 * Question view model
 */
class Question extends \XLite\Module\QSL\ProductQuestions\View\Model\Question implements \XLite\Base\IDecorator
{
    /**
     * Shema default
     *
     * @var array
     */
    protected $schemaDefault = array(
        'private' => array(
            self::SCHEMA_CLASS    => 'XLite\Module\QSL\ProductQuestions\View\FormField\Select\QuestionType',
            self::SCHEMA_LABEL    => 'Question type',
            self::SCHEMA_REQUIRED => false,
        ),
        'name' => array(
            self::SCHEMA_CLASS    => 'XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Name',
            self::SCHEMA_REQUIRED => true,
        ),
        'email' => array(
            self::SCHEMA_CLASS    => 'XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Email',
            self::SCHEMA_REQUIRED => false,
        ),
        'mobile' => array(
            self::SCHEMA_CLASS    => 'XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Mobile',
            self::SCHEMA_REQUIRED => false,
        ),
        'product' => array(
            self::SCHEMA_CLASS    => 'XLite\View\FormField\Select\Model\ProductSelector',
            self::SCHEMA_LABEL    => 'Product',
            self::SCHEMA_REQUIRED => true,
        ),
        'question' => array(
            self::SCHEMA_CLASS    => 'XLite\View\FormField\Textarea\Simple',
            self::SCHEMA_LABEL    => 'Question',
            self::SCHEMA_REQUIRED => true,
        ),
        'answer' => array(
            self::SCHEMA_CLASS    => 'XLite\View\FormField\Textarea\Simple',
            self::SCHEMA_LABEL    => 'Answer',
            self::SCHEMA_REQUIRED => false,
        ),
    );

    /**
     * Return list of the "Button" widgets
     *
     * @return array
     */
    protected function getFormButtons()
    {
        $result = parent::getFormButtons();

        $label = $this->getModelObject()->getId() ? 'Submit' : 'Create';

        $result['submit'] = new \XLite\View\Button\Submit(
            array(
                \XLite\View\Button\AButton::PARAM_LABEL => $label,
                \XLite\View\Button\AButton::PARAM_STYLE => 'action',
            )
        );

        return $result;
    }


}
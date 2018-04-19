<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Author Md Shofiul Alam
 * Copyright (c) 2011-present Toolmates Hire. All rights reserved.
 * See https://toolmateshire.com.au for license details.
 */

namespace XLite\Module\Shofi\TwilioSMS\Core;

/**
 * Decorated Mailer class.
 */
class Mailer extends \XLite\Core\Mailer implements \XLite\Base\IDecorator
{
    /**
     * FROM: Site administrator
     */
    const TYPE_ORDER_CREATED_ADMIN      = 'siteAdmin';

    /**
     * FROM: Orders department
     */
    const TYPE_ORDER_CREATED_CUSTOMER   = 'ordersDep';

    /**
     * Notify the customer about the new answer on his/her product question.
     *
     * @param \XLite\Module\QSL\ProductQuestions\Model\Question $question Question model
     *
     * @return void
     */
    public static function sendProductQuestionAnswerCustomer(\XLite\Module\QSL\ProductQuestions\Model\Question $question)
    {
        $profile = $question->getProfile();
        $email = $question->getEmail() ?: ($profile ? $profile->getLogin() : false);

        if ($email && $question->getAnswer()) {

            static::register(
                array(
                    'question'     => $question,
                    'profile'      => $profile,
                    'product'      => $question->getProduct(),
                    'name'         => array('firstname' => $question->getName() ?: ($profile ? $profile->getName() : '')),
                    'answer'       => $question->getAnswer(),
                    'questionText' => static::wrapUtf8StringByWords($question->getQuestion(), 75, "\n> "),
                )
            );

            static::compose(
                static::TYPE_ORDER_CREATED_CUSTOMER,
                static::getQuestionVendorMail($question),
                $email,
                'modules/QSL/ProductQuestions/answer',
                array(),
                true,
                \XLite::CUSTOMER_INTERFACE,
                static::getMailer()->getLanguageCode(
                    \XLite::CUSTOMER_INTERFACE,
                    $profile ? $profile->getLanguage() : ''
                )
            );
        }
    }

    /**
     * Notify the store owner about new product question.
     *
     * @param \XLite\Module\QSL\ProductQuestions\Model\Question $question Question model
     *
     * @return void
     */
    public static function sendNewCallbackRequestAdmin(\XLite\Module\Shofi\TwilioSMS\Model\CallbackRequest $callbackRequest)
    {


        static::register(
            array(
                'question' => $callbackRequest,
                'product'  => $callbackRequest->getProduct(),
                'name'     => $callbackRequest->getName(),
                'url'      => \XLite\Core\Converter::buildFullURL(
                    'callback_request',
                    '',
                    array(
                        'id' => $callbackRequest->getId(),
                    ),
                    \XLite::getAdminScript()
                )
            )
        );

        static::compose(
            static::TYPE_ORDER_CREATED_CUSTOMER,
            static::getSiteAdministratorMail(),
            static::getQuestionVendorMail($callbackRequest),
            'modules/Shofi/TwilioSMS/new_request',
            array(),
            true,
            \XLite::ADMIN_INTERFACE,
            static::getMailer()->getLanguageCode(\XLite::ADMIN_INTERFACE)
        );
    }

    /**
     * Split an UTF-8 string into multiple lines by words.
     *
     * @param string  $string UTF-8 string
     * @param integer $width  Maximum number of characters per line OPTIONAL
     * @param string  $break  Line-break character OPTIONAL
     * @param boolean $cut    Whether to break long words, or not OPTIONAL
     *
     * @return string
     */
    protected static function wrapUtf8StringByWords($string, $width = 75, $break = "\n", $cut = false)
    {
        if ($cut) {
            $search = '/(.{1,' . $width . '})(?:\s|$)|(.{' . $width . '})/uS';
            $replace =  '$1$2' . $break;
        } else {
            $search = '/(?=\s)(.{1,' . $width . '})(?:\s|$)/uS';
            $replace = '$1' . $break;
        }

        $lines = explode("\n", $string);
        $r = '';
        foreach ($lines as $line) {
            $r .= $break . preg_replace($search, $replace, $line);
        }

        return $r;
    }

    /**
     * Returns the e-mail of the person who is to reply on the product question.
     *
     * @param \XLite\Module\QSL\ProductQuestions\Model\Question $question Product question
     *
     * @return string
     */
    protected static function getQuestionVendorMail(\XLite\Module\QSL\ProductQuestions\Model\Question $question)
    {
        $config = \XLite\Core\Config::getInstance()->Company;

        return $config->product_questions_admin_email ?: $config->orders_department;
    }

}

<?php namespace App\Model;

class FAQPage extends Page
{
    public static $field_faqs;
    public static $field_faq_question;
    public static $field_faq_answer;

    public static function bootstrap()
    {
        self::$field_faqs = Config::getKeyPrefix() . 'faqs';
        self::$field_faq_question = Config::getKeyPrefix() . 'question';
        self::$field_faq_answer = Config::getKeyPrefix() . 'answer';
    }

    public function getQuestionAndAnswers()
    {
        $group_field = self::$field_faqs;
    }
}
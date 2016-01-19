<?php namespace App\Model;

class FAQPage extends Page
{
    const PAGE_ID = 21;

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
        $group = $this->$group_field;

        $faqs = array();

        if ($group === false) {
            return array();
        }

        foreach ($group as $item) {
            if (
                isset($item[self::$field_faq_question]) &&
                isset($item[self::$field_faq_answer]) &&
                $item[self::$field_faq_question] !== '' &&
                $item[self::$field_faq_answer] !== ''
            ) {
                $faqs[] = array(
                    'question' => $item[self::$field_faq_question],
                    'answer' => $item[self::$field_faq_answer]
                );
            }
        }

        return $faqs;
    }
}
<?php namespace App\Model;

class NewsAndEventsPage extends Page
{
    public static $field_contact_form_title;
    public static $field_contact_form_gravity_form_id;

    public static function bootstrap()
    {
        self::$field_contact_form_title = Config::getKeyPrefix() . 'contact_form_title';
        self::$field_contact_form_gravity_form_id = Config::getKeyPrefix() . 'contact_form_gravity_form_id';
    }

    public function getContactFormTitle()
    {
        $field = self::$field_contact_form_title;
        return $this->$field;
    }

    public function getContactFormId()
    {
        $field = self::$field_contact_form_gravity_form_id;
        return $this->$field;
    }
}
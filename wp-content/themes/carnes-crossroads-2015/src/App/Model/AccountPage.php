<?php namespace App\Model;

class AccountPage extends Page
{
    public static $field_what_s_new_content;
    public static $field_favorites_content;
    public static $field_saved_searches_content;
    public static $field_notifications_on_listings_options_content;
    public static $field_change_email_password_content;
    public static $field_create_account_content;

    public static function bootstrap()
    {
        self::$field_what_s_new_content = Config::getKeyPrefix() . 'what_s_new_content';
        self::$field_favorites_content = Config::getKeyPrefix() . 'favorites_content';
        self::$field_saved_searches_content = Config::getKeyPrefix() . 'saved_searches_content';
        self::$field_notifications_on_listings_options_content = Config::getKeyPrefix() . 'notifications_on_listings_options_content';
        self::$field_change_email_password_content = Config::getKeyPrefix() . 'change_email_password_content';
        self::$field_create_account_content = Config::getKeyPrefix() . 'create_account_content';
    }

    public function getCreateAccountContent()
    {
        $field = self::$field_create_account_content;
        return $this->$field;
    }

    public function getWhatsNewContent()
    {
        $field = self::$field_what_s_new_content;
        return $this->$field;
    }

    public function getFavoritesContent()
    {
        $field = self::$field_favorites_content;
        return $this->$field;
    }

    public function getSavedSearchesContent()
    {
        $field = self::$field_saved_searches_content;
        return $this->$field;
    }

    public function getNotificationsOnListingsOptionsContent()
    {
        $field = self::$field_notifications_on_listings_options_content;
        return $this->$field;
    }

    public function getChangeEmailPasswordContent()
    {
        $field = self::$field_change_email_password_content;
        return $this->$field;
    }
}
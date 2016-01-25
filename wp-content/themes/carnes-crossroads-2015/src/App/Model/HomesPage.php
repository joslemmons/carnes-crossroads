<?php namespace App\Model;

class HomesPage extends Page
{
    public static $field_hero;
    public static $field_hero_headline;
    public static $field_hero_quicklinks;
    public static $field_hero_quicklink_link;
    public static $field_hero_quicklink_title;

    public static function bootstrap()
    {
        self::$field_hero = Config::getKeyPrefix() . 'hero';
        self::$field_hero_headline = Config::getKeyPrefix() . 'hero_headline';
        self::$field_hero_quicklinks = Config::getKeyPrefix() . 'hero_quicklinks';
        self::$field_hero_quicklink_link = Config::getKeyPrefix() . 'hero_quicklink_link';
        self::$field_hero_quicklink_title = Config::getKeyPrefix() . 'hero_quicklink_title';
    }

    public function getHero()
    {
        $return = array();

        $hero_group = self::$field_hero;
        $group = $this->$hero_group;

        $headline = $group[self::$field_hero_headline];
        $return['headline'] = $headline;

        $return['quicklinks'] = array();
        $quicklinks = $group[self::$field_hero_quicklinks];
        foreach ($quicklinks as $quicklink) {
            if (
                isset($quicklink[self::$field_hero_quicklink_title]) &&
                isset($quicklink[self::$field_hero_quicklink_link])
            ) {
                $return['quicklinks'][] = array(
                    'title' => $quicklink[self::$field_hero_quicklink_title],
                    'link' => $quicklink[self::$field_hero_quicklink_link]
                );
            }
        }

        return $return;
    }
}
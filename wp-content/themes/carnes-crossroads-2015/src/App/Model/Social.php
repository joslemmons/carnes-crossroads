<?php namespace App\Model;

use Carbon\Carbon;

trait SocialSettings
{
    private static $_settings = null;

    public $social_post_name;
    public $social_post_message;
    public $social_post_type;
    public $social_post_image_src;
    public $social_post_created_time;
    public $social_post_link;

    public static function _getSocialSettingByKey($key)
    {
        $settings = self::_getSocialSettings();
        return (isset($settings[$key])) ? $settings[$key] : null;
    }

    public static function _getSocialSettings()
    {
        if (self::$_settings === null) {
            self::$_settings = get_option(Social::SETTINGS_FIELD);
        }

        return self::$_settings;
    }

}

class Social
{
    const SETTINGS_FIELD = 'app_social_options';

    public static function init()
    {
        add_action('piklist_admin_pages', array(__CLASS__, '_registerSettingsPage'), 10, 1);
    }

    public static function _registerSettingsPage($pages)
    {
        $pages[] = array(
            'page_title' => __('Social', Config::getTextDomain()),
            'menu_title' => __('Social', Config::getTextDomain()),
            'capability' => 'manage_options',
            'menu_slug' => 'social-options',
            'setting' => 'app_social_options',
            'save_text' => 'Save settings'
        );

        return $pages;
    }

    public static function getSocialFeed()
    {
        $recent_tweets = Twitter::getRecentTweetsByUser(Twitter::getDefaultUsername(), 6);
        $recent_facebook_posts = Facebook::getRecentPostsForPage(Facebook::getDefaultUsername(), 6);
        $recent_instagram_posts = Instagram::getRecentByUser(Instagram::getDefaultUsername(), 6);
        $recent_youtube_videos = YouTube::getRecentVideosForChannel(YouTube::getDefaultUsername(), 6);

        // filter out tweets that are the same as facebook
        $recent_tweets = array_filter($recent_tweets, function ($tweet) use ($recent_facebook_posts) {
            foreach ($recent_facebook_posts as $facebook_post) {
                if (substr($tweet->social_post_message, 0, 10) === substr($facebook_post->social_post_message, 0, 10)) {
                    return false;
                }
            }

            return true;
        });

        $recent_social_posts = array();
        if (false === empty($recent_tweets)) {
            $recent_social_posts[] = array_shift($recent_tweets);
        }

        if (false === empty($recent_facebook_posts)) {
            $recent_social_posts[] = array_shift($recent_facebook_posts);
        }

        if (false === empty($recent_instagram_posts)) {
            $recent_social_posts[] = array_shift($recent_instagram_posts);
        }

        if (false === empty($recent_youtube_videos)) {
            $recent_social_posts[] = array_shift($recent_youtube_videos);
        }

        $social_what_s_left = array_merge($recent_tweets, $recent_facebook_posts, $recent_instagram_posts, $recent_youtube_videos);
        shuffle($social_what_s_left);
        while (count($recent_social_posts) < 6) {
            $recent_social_posts[] = array_shift($social_what_s_left);
        }

        // put in desc order
        usort($recent_social_posts, function ($el1, $el2) {
            $el1_created_time = Carbon::createFromTimestamp(strtotime($el1->social_post_created_time));
            $el2_created_time = Carbon::createFromTimestamp(strtotime($el2->social_post_created_time));
            if ($el1_created_time === $el2_created_time) {
                return 0;
            } elseif ($el1_created_time > $el2_created_time) {
                return -1;
            } else {
                return 1;
            }
        });

        return $recent_social_posts;
    }
}

<?php namespace App\Model;

class Post extends \TimberPost
{
    public static $field_is_featured;
    public static $field_gallery_image_attachment_ids;

    const IS_FEATURED = 'yes';
    const IS_NOT_FEATURED = 'no';

    public static function bootstrap()
    {
        self::$field_is_featured = Config::getKeyPrefix() . 'is_featured';
        self::$field_gallery_image_attachment_ids = Config::getKeyPrefix() . 'gallery_image_attachment_ids';
    }

    public static function getIsFeaturedOptionsForPiklist()
    {
        return array(
            self::IS_FEATURED => 'Yes',
            self::IS_NOT_FEATURED => 'No'
        );
    }

    /**
     * @return array
     */
    public function getGallery()
    {
        $field = self::$field_gallery_image_attachment_ids;
        $gallery = $this->$field;

        if (false === $gallery || '' === $gallery) {
            return array();
        }

        if ($gallery && false === is_array($gallery)) {
            $gallery = array($gallery);
        }

        $images = array();
        foreach ($gallery as $attachment_id) {
            if ('' !== $attachment_id) {
                $image = new \TimberImage($attachment_id);
                $images[] = $image;
            }
        }

        return $images;
    }

    /**
     * return bool
     */
    public function isFeatured()
    {
        $field = self::$field_is_featured;
        return ($this->$field === self::IS_FEATURED);
    }

    public static function getFeaturedNews($posts_per_page = -1)
    {
        return \Timber::get_posts(array(
            'post_type' => 'post',
            'posts_per_page' => $posts_per_page,
            'meta_query' => array(
                array(
                    'key' => self::$field_is_featured,
                    'compare' => '=',
                    'value' => self::IS_FEATURED
                )
            )
        ), get_class());
    }

    public static function getRecentNews()
    {
        return \Timber::get_posts(array(
            'post_type' => 'post'
        ), get_class());
    }
}
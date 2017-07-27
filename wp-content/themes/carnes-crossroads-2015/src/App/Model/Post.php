<?php namespace App\Model;

use Carbon\Carbon;
use HomeFinder\Model\HomeFinder;
use HomeFinder\Model\Property;

class Post extends \TimberPost
{
    public static $field_is_featured;
    public static $field_gallery_image_attachment_ids;

    public static $field_is_recent_sales_activity_post;

    public static $field_title;
    public static $field_sub_title;
    public static $field_image_attachment_id;

    public static $field_recently_listed_group;
    public static $field_recently_listed_pick_or_manual;
    public static $field_recently_listed_pick_property_base_id;
    public static $field_recently_listed_manual_property_base_id;

    public static $field_recently_sold_group;
    public static $field_recently_sold_pick_or_manual;
    public static $field_recently_sold_pick_property_base_id;
    public static $field_recently_sold_manual_property_base_id;

    public static $field_footer_section_image_attachment_id;
    public static $field_footer_section_title;
    public static $field_footer_section_description;
    public static $field_footer_section_gravity_form_id;

    const IS_LINK_TO_PAGE = 'page';
    const IS_CUSTOM_LINK = 'custom';
    const GALLERY_NO_VIDEO = 'no video';
    const GALLERY_UPLOAD_VIDEO = 'upload video';
    const GALLERY_LINK_VIDEO = 'link video';
    const IS_FEATURED = 'yes';
    const IS_NOT_FEATURED = 'no';
    const IS_RECENT_SALES_ACTIVITY_POST = 'yes';
    const IS_NOT_RECENT_SALES_ACTIVITY_POST = 'no';
    const CHOOSE_FROM_LIST = 'choose';
    const MANUAL = 'manual';

    public static function bootstrap()
    {
        self::$field_is_featured = Config::getKeyPrefix() . 'is_featured';
        self::$field_gallery_image_attachment_ids = Config::getKeyPrefix() . 'gallery_image_attachment_ids';

        self::$field_title = Config::getKeyPrefix() . 'title';
        self::$field_sub_title = Config::getKeyPrefix() . 'sub_title';
        self::$field_image_attachment_id = Config::getKeyPrefix() . 'image_attachment_id';
        self::$field_is_recent_sales_activity_post = Config::getKeyPrefix() . 'is_recent_sales_activity_post';

        self::$field_recently_listed_group = Config::getKeyPrefix() . 'recently_listed_group';
        self::$field_recently_listed_pick_or_manual = Config::getKeyPrefix() . 'recently_listed_pick_or_manual';
        self::$field_recently_listed_pick_property_base_id = Config::getKeyPrefix() . 'recently_listed_pick_property_base_id';
        self::$field_recently_listed_manual_property_base_id = Config::getKeyPrefix() . 'recently_listed_manual_property_base_id';

        self::$field_recently_sold_group = Config::getKeyPrefix() . 'recently_sold_group';
        self::$field_recently_sold_pick_or_manual = Config::getKeyPrefix() . 'recently_sold_pick_or_manual';
        self::$field_recently_sold_pick_property_base_id = Config::getKeyPrefix() . 'recently_sold_pick_property_base_id';
        self::$field_recently_sold_manual_property_base_id = Config::getKeyPrefix() . 'recently_sold_manual_property_base_id';

        self::$field_footer_section_image_attachment_id = Config::getKeyPrefix() . '';
        self::$field_footer_section_title = Config::getKeyPrefix() . 'footer_section_title';
        self::$field_footer_section_description = Config::getKeyPrefix() . 'footer_section_description';
        self::$field_footer_section_gravity_form_id = Config::getKeyPrefix() . 'footer_section_gravity_form_id';

        add_action('admin_menu', function () {
            remove_meta_box('postcustom', 'post', 'normal');
        });
    }

    public static function getPickPropertyBaseIdChoicesForPiklist()
    {
        return array(
            self::CHOOSE_FROM_LIST => 'Choose from List',
            self::MANUAL => 'Enter Property Base ID'
        );
    }

    public static function getIsFeaturedOptionsForPiklist()
    {
        return array(
            self::IS_FEATURED => 'Yes',
            self::IS_NOT_FEATURED => 'No'
        );
    }

    public static function getIsRecentSalesActivityOptionsForPiklist()
    {
        return array(
            self::IS_RECENT_SALES_ACTIVITY_POST => 'Yes',
            self::IS_NOT_RECENT_SALES_ACTIVITY_POST => 'No'
        );
    }

    /**
     * @param Post $post
     * @return array
     */
    public static function getRecentlyListedOptionsForPiklist($post = null)
    {
        $properties_to_include = array();
        if ($post) {
            $properties = $post->getRecentlyListedProperties();
            foreach ($properties as $property) {
                /* @var Property $property */
                $properties_to_include[$property->property_base_id] = $property->getAddress() . ' (' . $property->property_base_id . ')';
            }
        }

        $property_result = HomeFinder::getRecentlyListed(64);
        $properties = $property_result->items;

        $return = array();

        foreach ($properties as $property) {
            /* @var Property $property */
            $return[$property->property_base_id] = $property->getAddress() . ' (' . $property->property_base_id . ')';
        }

        // the result of most recent properties may not include what was originally set. Make sure it's there
        foreach ($properties_to_include as $property_base_id => $address_and_id) {
            if (isset($return[$property_base_id]) === false) {
                $return[$property_base_id] = $address_and_id;
            }
        }

        return $return;
    }

    /**
     * @param Post $post
     * @return array
     */
    public static function getRecentlySoldOptionsForPiklist($post = null)
    {
        $properties_to_include = array();
        if ($post) {
            $properties = $post->getRecentlySoldProperties();
            foreach ($properties as $property) {
                /* @var Property $property */
                $properties_to_include[$property->property_base_id] = $property->getAddress() . ' (' . $property->property_base_id . ')';
            }
        }

        $property_result = HomeFinder::getRecentlySold(100);
        $properties = $property_result->items;

        $return = array();

        foreach ($properties as $property) {
            /* @var Property $property */
            $return[$property->property_base_id] = $property->getAddress() . ' (' . $property->property_base_id . ')';
        }

        // the result of most recent properties may not include what was originally set. Make sure it's there
        foreach ($properties_to_include as $property_base_id => $address_and_id) {
            if (isset($return[$property_base_id]) === false) {
                $return[$property_base_id] = $address_and_id;
            }
        }

        return $return;
    }

    /**
     * @return Property[]
     */
    public function getRecentlyListedProperties()
    {
        $field = self::$field_recently_listed_group;
        $recently_listed_properties = $this->$field;

        if (!$recently_listed_properties) {
            return array();
        }

        $properties = array();
        foreach ($recently_listed_properties as $recently_listed_property) {

            $choice = self::CHOOSE_FROM_LIST;
            if (
                isset($recently_listed_property[self::$field_recently_listed_manual_property_base_id]) &&
                $recently_listed_property[self::$field_recently_listed_manual_property_base_id] !== ''
            ) {
                $choice = self::MANUAL;
            }

            if ($choice === self::MANUAL) {
                $properties[] = Property::withId('pb_' . $recently_listed_property[self::$field_recently_listed_manual_property_base_id]);
            } else {
                $properties[] = Property::withId('pb_' . $recently_listed_property[self::$field_recently_listed_pick_property_base_id]);
            }
        }

        return $properties;
    }

    /**
     * @return Property[]
     */
    public function getRecentlySoldProperties()
    {
        $field = self::$field_recently_sold_group;
        $recently_sold_properties = $this->$field;

        if (!$recently_sold_properties) {
            return array();
        }

        $properties = array();
        foreach ($recently_sold_properties as $recently_sold_property) {
            $choice = self::CHOOSE_FROM_LIST;
            if (
                isset($recently_sold_property[self::$field_recently_sold_manual_property_base_id]) &&
                $recently_sold_property[self::$field_recently_sold_manual_property_base_id] !== ''
            ) {
                $choice = self::MANUAL;
            }

            if ($choice === self::MANUAL) {
                $properties[] = Property::withId('pb_' . $recently_sold_property[self::$field_recently_sold_manual_property_base_id]);
            } else {
                $properties[] = Property::withId('pb_' . $recently_sold_property[self::$field_recently_sold_pick_property_base_id]);
            }
        }

        return $properties;
    }

    public static function getHasVideoOptionsForPiklist()
    {
        return array(
            self::GALLERY_NO_VIDEO => 'No Video',
            self::GALLERY_UPLOAD_VIDEO => 'Upload/Choose a Video',
            self::GALLERY_LINK_VIDEO => 'Insert link to Video'
        );
    }

    public static function getButtonLinkOptionsForPiklist()
    {
        return array(
            self::IS_LINK_TO_PAGE => 'Link to Internal Page',
            self::IS_CUSTOM_LINK => 'Custom Link'
        );
    }

    public static function getFormChoicesForPiklist()
    {
        $forms = array();
        $gravity_forms = \GFAPI::get_forms();

        $forms[0] = '-- Choose Gravity Form --';

        foreach ($gravity_forms as $gravity_form) {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $gravity_form['date_created']);
            $forms[$gravity_form['id']] = $gravity_form['title'] . ' (' . $date->format('M j, Y') . ')';
        }

        return $forms;
    }

    public function getHeaderImage()
    {
        $field = self::$field_image_attachment_id;
        $image_attachment_id = $this->$field;

        if ($image_attachment_id) {
            return new \TimberImage($image_attachment_id);
        }

        return false;
    }

    public function getTitle()
    {
        $field = self::$field_title;
        return $this->$field;
    }

    public function getSubTitle()
    {
        $field = self::$field_sub_title;
        return $this->$field;
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

    public static function getFeaturedNews()
    {
        return \Timber::get_posts(array(
            'post_type' => 'post',
            'posts_per_page' => -1,
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

    public function isRecentActivityPost()
    {
        $field = self::$field_is_recent_sales_activity_post;
        return ($this->$field === self::IS_RECENT_SALES_ACTIVITY_POST);
    }

    public function getFooter()
    {
        $footer_data = array();

        $field_title = self::$field_footer_section_title;
        $footer_data['title'] = $this->$field_title;

        $field_content = self::$field_footer_section_description;
        $footer_data['content'] = $this->$field_content;

        $field_image_attachment_id = self::$field_footer_section_image_attachment_id;
        $image_attachment_id = $this->$field_image_attachment_id;
        if ($image_attachment_id) {
            if (is_array($image_attachment_id)) {
                $image_attachment_id = array_pop($image_attachment_id);
            }
            $footer_data['image'] = new \TimberImage($image_attachment_id);
        }

        $field_gravity_form_id = self::$field_footer_section_gravity_form_id;
        $footer_data['gravity_form_id'] = $this->$field_gravity_form_id;

        return $footer_data;
    }

    public function getQuicklinkBoxes()
    {
        $box_one = array(array(
            'title' => 'Community Map',
            'subtitle' => 'See the Bigger Picture',
            'image' => array(
                'get_src' => get_template_directory_uri() . '/img/bg-button-map.jpg'
            ),
            'button' => array(
                'link' => get_template_directory_uri() . '/img/map-master-plan.jpg'
            )
        ));

        $box_two = array(array(
            'title' => 'Plan your Visit',
            'subtitle' => 'Contact us today',
            'image' => array(
                'get_src' => get_template_directory_uri() . '/img/bg-button-visit.jpg'
            ),
            'button' => array(
                'link' => '/contact/'
            )
        ));

        $box_three = array(array(
            'title' => 'Upcoming Events',
            'subtitle' => 'Concerts, Classes & More',
            'image' => array(
                'get_src' => get_template_directory_uri() . '/img/bg-button-events.jpg'
            ),
            'button' => array(
                'link' => '/calendar-of-events/'
            )
        ));
        
        return array_merge($box_one, $box_two, $box_three);
    }
}

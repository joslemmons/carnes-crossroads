<?php namespace App\Model;

class Builder extends Page
{
    public static $postType;
    public static $labelTitle;

    public static $field_floor_plans;
    public static $field_floor_plan_title;
    public static $field_floor_plan_description;
    public static $field_floor_plan_featured_image_attachment_ids;
    public static $field_floor_plan_price;
    public static $field_floor_plan_bedrooms;
    public static $field_floor_plan_full_bathrooms;
    public static $field_floor_plan_half_bathrooms;
    public static $field_floor_plan_square_footage;
    public static $field_floor_plan_is_master_downstairs;
    public static $field_floor_plan_is_single_story;
    public static $field_floor_plan_brochure_attachment_id;
    public static $field_floor_plan_floor_plan_attachment_id;

    public static $field_show_available_homes_button;
    public static $field_show_home_plans_button;
    public static $field_standard_featured_attachment_id;
    public static $field_featured_video_src;

    const YES = 'yes';
    const NO = 'no';

    public static function bootstrap()
    {
        self::$postType = 'builder';
        self::$labelTitle = 'Builder';

        self::$field_floor_plans = Config::getKeyPrefix() . 'floor_plans';
        self::$field_floor_plan_title = Config::getKeyPrefix() . 'floor_plan_title';
        self::$field_floor_plan_description = Config::getKeyPrefix() . 'floor_plan_description';
        self::$field_floor_plan_featured_image_attachment_ids = Config::getKeyPrefix() . 'floor_plan_featured_image_attachment_ids';
        self::$field_floor_plan_price = Config::getKeyPrefix() . 'floor_plan_price';
        self::$field_floor_plan_bedrooms = Config::getKeyPrefix() . 'floor_plan_bedrooms';
        self::$field_floor_plan_full_bathrooms = Config::getKeyPrefix() . 'floor_plan_full_bathrooms';
        self::$field_floor_plan_half_bathrooms = Config::getKeyPrefix() . 'floor_plan_half_bathrooms';
        self::$field_floor_plan_square_footage = Config::getKeyPrefix() . 'floor_plan_square_footage';
        self::$field_floor_plan_is_master_downstairs = Config::getKeyPrefix() . 'floor_plan_is_master_downstairs';
        self::$field_floor_plan_is_single_story = Config::getKeyPrefix() . 'floor_plan_is_single_story';
        self::$field_floor_plan_brochure_attachment_id = Config::getKeyPrefix() . 'floor_plan_brochure_attachment_id';
        self::$field_floor_plan_floor_plan_attachment_id = Config::getKeyPrefix() . 'floor_plan_floor_plan_attachment_id';

        self::$field_show_available_homes_button = Config::getKeyPrefix() . 'show_available_homes_button';
        self::$field_show_home_plans_button = Config::getKeyPrefix() . 'show_home_plans_button';
        self::$field_standard_featured_attachment_id = Config::getKeyPrefix() . 'standard_featured_attachment_id';
        self::$field_featured_video_src = Config::getKeyPrefix() . 'featured_video_src';

        add_filter('piklist_post_types', array(get_class(), 'registerCPT'));
    }

    public static function registerCPT($post_types)
    {
        $post_types[self::$postType] = array(
            'labels' => piklist('post_type_labels', self::$labelTitle),
            'title' => __('Title of Builder'),
            'public' => true,
            'menu_icon' => 'dashicons-store',
            'rewrite' => array(
                'slug' => 'builder'
            ),
            'hierarchical' => true,
            'supports' => array(
                'title',
                'editor',
                'thumbnail'
            ),
            'hide_meta_box' => array(
                'author',
                'comments',
                'commentstatus',
            )
        );

        return $post_types;
    }

    /**
     * @param $name
     * @return Builder|bool|null
     */
    public static function withName($name)
    {
        $post = get_page_by_title($name, 'object', self::$postType);

        if ($post === null) {
            return false;
        }

        return \Timber::get_post(array(
            'p' => $post->ID,
            'post_type' => self::$postType
        ), get_class());
    }

    public static function all()
    {
        return \Timber::get_posts(array(
            'posts_per_page' => -1,
            'post_type' => self::$postType,
            'orderby' => 'menu_order'
        ), get_class());
    }

    public function getFeaturedVideoSrc()
    {
        $field = self::$field_featured_video_src;
        return $this->$field;
    }

    public function getStandardFeaturesSrc()
    {
        $field = self::$field_standard_featured_attachment_id;
        $attachment_ids = $this->$field;

        if (is_array($attachment_ids) === true) {
            $attachment_ids = array_pop($attachment_ids);
        }

        return wp_get_attachment_url($attachment_ids);
    }

    public function shouldShowAvailableHomesButton()
    {
        $field = self::$field_show_available_homes_button;
        $should = $this->$field;

        return ($should === self::YES);
    }

    public function shouldShowHomePlansButton()
    {
        $field = self::$field_show_home_plans_button;
        $should = $this->$field;

        return ($should === self::YES);
    }

    public function getGallery()
    {
        $data = array();

        $data[] = array(
            'image' => $this->thumbnail()
        );

        return $data;
    }

    public static function getBedroomChoicesForPiklist()
    {
        $return = array();
        $numbers = range(0, 10);
        foreach ($numbers as $number) {
            $plural = '';
            if ($number > 1 || $number === 0) {
                $plural = 's';
            }
            $return[$number] = $number . ' Bedroom' . $plural;
        }

        return $return;
    }

    public static function getFullBathroomChoicesForPiklist()
    {
        $return = array();
        $numbers = range(0, 10);
        foreach ($numbers as $number) {
            $plural = '';
            if ($number > 1 || $number === 0) {
                $plural = 's';
            }
            $return[$number] = $number . ' Full Bathroom' . $plural;
        }

        return $return;
    }

    public static function getHalfBathroomChoicesForPiklist()
    {
        $return = array();
        $numbers = range(0, 10);
        foreach ($numbers as $number) {
            $plural = '';
            if ($number > 1 || $number === 0) {
                $plural = 's';
            }
            $return[$number] = $number . ' Half Bathroom' . $plural;
        }

        return $return;
    }

    /**
     * @param $name
     * @return FloorPlan|null
     */
    public function getFloorPlanByName($name)
    {
        $builder_floor_plans = $this->getFloorPlans();

        if (!$builder_floor_plans) {
            return null;
        }

        $floor_plan = null;
        foreach ($builder_floor_plans as $builder_floor_plan) {
            /* @var FloorPlan $builder_floor_plan */
            if (strtolower(trim($builder_floor_plan->title)) === strtolower(trim($name))) {
                $floor_plan = $builder_floor_plan;
                break;
            }
        }

        if (!$floor_plan) {
            return null;
        }

        return $floor_plan;
    }

    public function getFloorPlans()
    {
        $group_field = self::$field_floor_plans;
        $group = $this->$group_field;

        $floor_plans = array();
        foreach ($group as $item) {
            $floor_plan = new FloorPlan();
            $floor_plan->title = $item[self::$field_floor_plan_title];
            $floor_plan->description = $item[self::$field_floor_plan_description];
            $floor_plan->square_footage = $item[self::$field_floor_plan_square_footage];

            $featured_image_attachment_ids = $item[self::$field_floor_plan_featured_image_attachment_ids];
            if (is_array($featured_image_attachment_ids) === false) {
                $temp_array = array();
                $temp_array[] = $featured_image_attachment_ids;
                $featured_image_attachment_ids = $temp_array;
            }
            $featured_images = array();
            foreach ($featured_image_attachment_ids as $attachment_id) {
                $image = new \TimberImage($attachment_id);
                if ($image) {
                    $featured_images[] = $image;
                }
            }
            $floor_plan->featured_images = $featured_images;

            $floor_plan->price = $item[self::$field_floor_plan_price];
            $floor_plan->bedrooms = $item[self::$field_floor_plan_bedrooms];
            $floor_plan->full_bathrooms = $item[self::$field_floor_plan_full_bathrooms];
            $floor_plan->half_bathrooms = $item[self::$field_floor_plan_half_bathrooms];
            $floor_plan->is_master_downstairs = ($item[self::$field_floor_plan_is_master_downstairs] === self::YES);
            $floor_plan->is_single_story = ($item[self::$field_floor_plan_is_single_story] === self::YES);

            $brochure_attachment_id = $item[self::$field_floor_plan_brochure_attachment_id];
            if (is_array($brochure_attachment_id) === true) {
                $brochure_attachment_id = array_pop($brochure_attachment_id);
            }
            $floor_plan->brochure_src = wp_get_attachment_url($brochure_attachment_id);

            $floor_plan_attachment_id = $item[self::$field_floor_plan_floor_plan_attachment_id];
            if (is_array($floor_plan_attachment_id) === true) {
                $floor_plan_attachment_id = array_pop($floor_plan_attachment_id);
            }
            $floor_plan->floor_plan_src = wp_get_attachment_url($floor_plan_attachment_id);

            $floor_plan->builder = $this;

            $floor_plans[] = $floor_plan;
        }

        return $floor_plans;
    }

    public static function getYesOrNoChoicesForPiklist()
    {
        return array(
            self::YES => 'Yes',
            self::NO => 'No'
        );
    }


}
<?php namespace App\Model;

class RealEstateAgent extends \TimberPost
{
    public static $postType;
    public static $labelTitle;
    public static $field_listing_agent_property_base_id;
    public static $field_contacts;
    public static $field_contact_name;
    public static $field_contact_office_number;
    public static $field_contact_mobile_number;
    public static $field_contact_email;

    public static function bootstrap()
    {
        self::$postType = 'agent';
        self::$labelTitle = 'Agent';
        self::$field_listing_agent_property_base_id = Config::getKeyPrefix() . 'listing_agent_property_base_id';
        self::$field_contacts = Config::getKeyPrefix() . 'contacts';
        self::$field_contact_name = Config::getKeyPrefix() . 'contact_name';
        self::$field_contact_office_number = Config::getKeyPrefix() . 'contact_office_number';
        self::$field_contact_mobile_number = Config::getKeyPrefix() . 'contact_mobile_number';
        self::$field_contact_email = Config::getKeyPrefix() . 'contact_email';

        add_filter('piklist_post_types', array(get_class(), 'registerCPT'));
    }

    public static function registerCPT($post_types)
    {
        $post_types[self::$postType] = array(
            'labels' => piklist('post_type_labels', self::$labelTitle),
            'title' => __('Name of Agent'),
            'public' => true,
            'menu_icon' => 'dashicons-businessman',
            'rewrite' => array(
                'slug' => 'agent'
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
     * @return array
     */
    public function getContacts()
    {
        $group = self::$field_contacts;
        $contacts = $this->$group;
        $field_name = self::$field_contact_name;
        $field_office_phone = self::$field_contact_office_number;
        $field_mobile_phone = self::$field_contact_mobile_number;
        $field_email = self::$field_contact_email;

        $return = array();
        if ($contacts && is_array($contacts)) {
            foreach ($contacts as $contact) {
                if (
                    isset($contact[$field_name]) &&
                    isset($contact[$field_office_phone]) &&
                    isset($contact[$field_mobile_phone]) &&
                    isset($contact[$field_email]) &&
                    $contact[$field_name] !== ''
                ) {
                    $return[] = array(
                        'name' => $contact[$field_name],
                        'office_phone' => $contact[$field_office_phone],
                        'mobile_phone' => $contact[$field_mobile_phone],
                        'email' => $contact[$field_email]
                    );
                }

            }
        }

        return $return;
    }

    /**
     * @param $property_base_listing_id
     * @return RealEstateAgent|null
     */
    public static function withPropertyBaseListingAgentId($property_base_listing_id)
    {
        $property_base_listing_id = trim($property_base_listing_id);

        return \Timber::get_post(array(
            'post_type' => self::$postType,
            'meta_key' => self::$field_listing_agent_property_base_id,
            'meta_query' => array(
                array(
                    'key' => self::$field_listing_agent_property_base_id,
                    'value' => $property_base_listing_id,
                    'compare' => '='
                )
            )
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

    public function getGallery()
    {
        $data = array();

        $data[] = array(
            'image' => $this->thumbnail()
        );

        return $data;
    }

    public function isRealEstateAgent()
    {
        return true;
    }

    /**
     * @return array
     */
    public function getButtons()
    {
        $field = self::$field_listing_agent_property_base_id;
        $listing_agent_id = $this->$field;

        return array(
            'button' => array(
                'action' => 'custom',
                'title' => 'View Listings',
                'custom_link' => home_url() . '/real-estate/home-finder/search-listings/?listingAgents[]=' . $listing_agent_id
            )
        );
    }
}

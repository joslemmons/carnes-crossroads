<?php namespace App\Model;

class NewOfferings extends Post
{
    public static function bootstrap()
    {
        // todo: make sure new-offerings slug exists
    }

    public static function all($per_page = 1, $page = 1)
    {
        return \Timber::get_posts(array(
            'post_type' => 'post',
            'category_name' => 'new-offerings',
            'posts_per_page' => $per_page,
            'paged' => $page,
            'orderby' => 'menu_order',
            'order' => 'ASC',
        ), get_class());
    }

    public static function find($id)
    {
        return \Timber::get_post($id, get_class());
    }
}
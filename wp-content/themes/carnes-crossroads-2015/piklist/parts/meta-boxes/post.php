<?php
/*
 * Title: Post Options (Carnes Crossroads 2015 Theme)
 * Post Type: post
 */

use App\Model\Post;

piklist('field', array(
    'type' => 'radio',
    'field' => Post::$field_is_featured,
    'label' => 'Feature Post?',
    'choices' => Post::getIsFeaturedOptionsForPiklist(),
    'value' => Post::IS_NOT_FEATURED
));

piklist('field', array(
    'type' => 'file',
    'field' => Post::$field_gallery_image_attachment_ids,
    'label' => 'Gallery'
));

$posts = Post::getFeaturedNews();

if (false === empty($posts)) {
    $featured_posts_html = '<ul>';
    foreach ($posts as $featured_post) {
        $featured_posts_html .= '<li><a href="' . get_edit_post_link($featured_post->id) . '">' . $featured_post->title() . '</a></li>';
    }
    $featured_posts_html .= '</ul>';

    piklist('field', array(
        'type' => 'html',
        'field' => '_',
        'label' => 'Current Featured Posts',
        'value' => $featured_posts_html
    ));
}

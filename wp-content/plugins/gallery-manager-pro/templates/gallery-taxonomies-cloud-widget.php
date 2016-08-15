<?php

/*

Available environment vars:
 - $widget
 - $options

*/

Echo $widget->before_widget;

if (!Empty($widget->title))
  Echo $widget->before_title . $widget->title . $widget->after_title;

?>

<ul>
  <?php WP_Tag_Cloud(Array(
    'taxonomy' => $options->taxonomy,
    'number'   => (Int) $options->number,
    'order'    => $options->order,
    'orderby'  => $options->orderby,
    #'exclude'  => $options->exclude
  )) ?>
</ul>

<?php Echo $widget->after_widget;

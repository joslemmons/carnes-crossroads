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
  <?php WP_List_Categories(Array(
    'taxonomy'   => $options->taxonomy,
    'show_count' => $options->show_count,
    'number'     => $options->number,
    'order'      => $options->order,
    'orderby'    => $options->orderby,
    #'exclude'    => $options->exclude,
    'title_li'   => ''
  )) ?>
</ul>

<?php Echo $widget->after_widget;

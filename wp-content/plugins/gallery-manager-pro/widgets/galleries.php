<?php Namespace WordPress\Plugin\GalleryManager;

use WP_Widget, WP_Query;

class Galleries_Widget extends WP_Widget {

  function __construct(){
    # Setup the Widget data
    parent::__construct (
      'galleries',
      I18n::t('Galleries'),
      Array('description' => I18n::t('Displays some of your galleries.'))
    );
  }

  static function registerWidget(){
    if (doing_Action('widgets_init'))
      Register_Widget(__CLASS__);
    else
      add_Action('widgets_init', Array(__CLASS__, __FUNCTION__));
  }

  function getDefaultOptions(){
    # Default settings
    return Array(
      'title' => I18n::t('Galleries'),
      'number' => 5,
      'orderby' => 'date',
      'order' => 'DESC'
    );
  }

  function loadOptions(&$arr_options){
    setType($arr_options, 'ARRAY');
    $arr_options = Array_Filter($arr_options);
    $arr_options = Array_Merge($this->getDefaultOptions(), $arr_options);
    setType($arr_options, 'OBJECT');
  }

  function Form($options){
    $this->loadOptions($options);
    ?>
    <p>
      <label for="<?php Echo $this->get_Field_Id('title') ?>"><?php _e('Title:') ?></label>
      <input type="text" id="<?php Echo $this->get_Field_Id('title') ?>" name="<?php Echo $this->get_Field_Name('title') ?>" value="<?php Echo Esc_Attr($options->title) ?>" class="widefat">
      <small><?php Echo I18n::t('Leave blank to use the widget default title.') ?></small>
    </p>

    <p>
      <label for="<?php Echo $this->get_Field_Id('number') ?>"><?php Echo I18n::t('Number of galleries:') ?></label>
      <input type="number" id="<?php Echo $this->get_Field_Id('number') ?>" name="<?php Echo $this->get_Field_Name('number')?>" value="<?php Echo Esc_Attr($options->number) ?>" min="1" step="1" max="<?php Echo PHP_INT_MAX ?>" class="widefat">
    </p>

    <p>
      <label for="<?php Echo $this->get_Field_Id('orderby') ?>"><?php Echo I18n::t('Order by:') ?></label>
      <select id="<?php Echo $this->get_Field_Id('orderby') ?>" name="<?php Echo $this->get_Field_Name('orderby') ?>" class="widefat">
        <option value="title" <?php Selected($options->orderby, 'title') ?>><?php _e('Title') ?></option>
        <option value="date" <?php Selected($options->orderby, 'date') ?>><?php _e('Date') ?></option>
        <option value="modified" <?php Selected($options->orderby, 'modified') ?>><?php _e('Modified') ?></option>
        <option value="rand" <?php Selected($options->orderby, 'rand') ?>><?php Echo I18n::t('Randomly') ?></option>
        <option value="comment_count" <?php Selected($options->orderby, 'comment_count') ?>><?php Echo I18n::t('Number of comments') ?></option>
      </select>
    </p>

    <p>
      <label for="<?php Echo $this->get_Field_Id('order') ?>"><?php Echo I18n::t('Order:') ?></label>
      <select id="<?php Echo $this->get_Field_Id('order') ?>" name="<?php Echo $this->get_Field_Name('order') ?>" class="widefat">
        <option value="ASC" <?php Selected($options->order, 'ASC') ?>><?php _e('Ascending') ?></option>
        <option value="DESC" <?php Selected($options->order, 'DESC') ?>><?php _e('Descending') ?></option>
      </select>
    </p>
    <?php
  }

  function Widget($widget, $options){
    # Load widget args
    setType($widget, 'OBJECT');

    # Load options
    $this->loadOptions($options);

    # query galleries
    $options->galleries = new WP_Query(Array(
      'post_type' => Gallery_Post_Type::post_type_name,
      'posts_per_page' => $options->number,
      'has_password' => False,
      'orderby' => $options->orderby,
      'order' => $options->order
    ));

    if (!$options->galleries->have_Posts()) return False;

    # generate widget title
    $widget->title = Apply_Filters('widget_title', $options->title, (Array) $options, $this->id_base);

    # Display Widget
    Echo Template::load('galleries-widget', Array(
      'widget' => $widget,
      'options' => $options
    ));

    # Reset the global post and query vars
    WP_Reset_Postdata();
  }

}

Galleries_Widget::registerWidget();

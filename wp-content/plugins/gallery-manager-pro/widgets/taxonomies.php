<?php Namespace WordPress\Plugin\GalleryManager;

use WP_Widget;

class Taxonomies_Widget extends WP_Widget {

  function __construct(){
    # Setup the Widget data
    parent::__construct (
      'gallery-taxonomies',
      I18n::t('Gallery Taxonomies'),
      Array('description' => I18n::t('Displays your gallery taxonomies like categories, tags, events, photographers, etc.'))
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
      'title'      => I18n::t('Gallery Taxonomies'),
      'taxonomy'   => False,
      'number'     => Null,
      'show_count' => False,
      'orderby'    => 'name',
      'order'      => 'ASC',
      #'exclude'    => False
    );
  }

  function loadOptions(&$arr_options){
    setType($arr_options, 'ARRAY');
    $arr_options = Array_Filter($arr_options);
    $arr_options = Array_Merge($this->getDefaultOptions(), $arr_options);
    setType($arr_options, 'OBJECT');
  }

  function Form($options){
    # Load options
    $this->loadOptions($options);
    ?>

    <p>
      <label for="<?php Echo $this->get_Field_Id('title') ?>"><?php _e('Title:') ?></label>
      <input type="text" id="<?php Echo $this->get_Field_Id('title') ?>" name="<?php Echo $this->get_Field_Name('title') ?>" value="<?php Echo Esc_Attr($options->title) ?>" class="widefat">
      <small><?php Echo I18n::t('Leave blank to use the widget default title.') ?></small>
    </p>

    <p>
      <label for="<?php Echo $this->get_Field_Id('taxonomy') ?>"><?php Echo I18n::t('Taxonomy:') ?></label>
      <select id="<?php Echo $this->get_Field_Id('taxonomy') ?>" name="<?php Echo $this->get_Field_Name('taxonomy') ?>" class="widefat">
      <?php foreach(get_Object_Taxonomies(Gallery_Post_Type::post_type_name) AS $taxonomy) : $taxonomy = get_Taxonomy($taxonomy) ?>
      <option value="<?php Echo $taxonomy->name ?>" <?php Selected($options->taxonomy, $taxonomy->name) ?>><?php Echo HTMLSpecialChars($taxonomy->labels->name) ?></option>
      <?php endforeach ?>
      </select><br>
      <small><?php Echo I18n::t('Please choose the taxonomy the widget should display.') ?></small>
    </p>

    <p>
      <label for="<?php Echo $this->get_Field_Id('number') ?>"><?php Echo I18n::t('Number of terms:') ?></label>
      <input type="number" id="<?php Echo $this->get_Field_Id('number') ?>" name="<?php Echo $this->get_Field_Name('number')?>" value="<?php Echo Esc_Attr($options->number) ?>" min="1" step="1" max="<?php Echo PHP_INT_MAX ?>" class="widefat">
      <small><?php Echo I18n::t('Leave blank to show all.') ?></small>
    </p>

    <?php /*
    <p>
      <label for="<?php echo $this->get_Field_Id('exclude') ?>"><?php _e('Exclude:') ?></label>
      <input type="text" value="<?php Echo Esc_Attr($options->exclude) ?>" name="<?php Echo $this->get_Field_Name('exclude') ?>" id="<?php Echo $this->get_Field_Id('exclude') ?>" class="widefat">
      <small><?php Echo I18n::t('Term IDs, separated by commas.') ?></small>
    </p>
    */ ?>

    <p>
      <input type="checkbox" id="<?php Echo $this->get_Field_Id('show_count') ?>" name="<?php Echo $this->get_Field_Name('show_count') ?>" value="1" <?php Checked($options->show_count) ?> >
      <label for="<?php echo $this->get_Field_Id('show_count') ?>"><?php Echo I18n::t( 'Show gallery counts.' ) ?></label>
    </p>

    <p>
      <label for="<?php Echo $this->get_Field_Id('orderby') ?>"><?php Echo I18n::t('Order by:') ?></label>
      <select id="<?php Echo $this->get_Field_Id('orderby') ?>" name="<?php Echo $this->get_Field_Name('orderby') ?>" class="widefat">
      <option value="name" <?php Selected($options->orderby, 'name') ?>><?php _e('Name') ?></option>
      <option value="count" <?php Selected($options->orderby, 'count') ?>><?php Echo I18n::t('Gallery count') ?></option>
      <option value="ID" <?php Selected($options->orderby, 'ID') ?>>ID</option>
      <option value="slug" <?php Selected($options->orderby, 'slug') ?>><?php Echo I18n::t('Slug') ?></option>
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

    # Check if the Taxonomy is alive
    if (!Taxonomy_Exists($options->taxonomy)) return False;

    # generate widget title
    $widget->title = Apply_Filters('widget_title', $options->title, (Array) $options, $this->id_base);

    # Display Widget
    Echo Template::load('gallery-taxonomies-widget', Array(
      'widget' => $widget,
      'options' => $options
    ));
  }

}

Taxonomies_Widget::registerWidget();

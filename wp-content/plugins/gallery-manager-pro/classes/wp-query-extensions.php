<?php Namespace WordPress\Plugin\GalleryManager;

abstract class WP_Query_Extensions {

  static function init(){
    add_Action('pre_get_posts', Array(__CLASS__, 'filterAttachmentQuery'));
  }

  static function filterAttachmentQuery($query){
    if (is_Admin() && $query->Get('post_type') == 'attachment' && $query->Get('orderby') == 'menu_order ASC, ID' && $query->Get('order') == 'DESC')
      $query->Set('order', 'ASC');
  }

}

WP_Query_Extensions::init();

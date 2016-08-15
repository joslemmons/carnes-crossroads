<?php Namespace WordPress\Plugin\GalleryManager;

abstract class Lightbox {

  static function init(){
    add_Action('wp_footer', Array(__CLASS__, 'printLightboxWrapper'));
  }

  static function printLightboxWrapper(){
    if (Options::Get('lightbox')): ?>
    <div class="bueimp-gallery gallery-lightbox-container controls">
      <div class="slides"></div>

      <div class="title-description">
        <?php if (Options::Get('title_description')): ?>
        <div class="title"></div>
        <a class="gallery"></a>
        <div class="description"></div>
        <?php endif ?>

        <?php /* if ($this->core->options->Get('share_links') == 'on'): ?>
        <div class="share-links">
          <a href="https://pinterest.com/pin/create/button/?url=%1$s&media=%2$s" class="facebook share icon">F</a>
          <a href="https://twitter.com/share?url=%1$s" class="twitter share icon">T</a>
          <a href="https://www.facebook.com/sharer/sharer.php?u=%s" class="pintarest share icon">P</a>
        </div>
        <?php endif */ ?>
      </div>

      <a class="prev" title="<?php Echo I18n::t('Previous image') ?>"></a>
      <a class="next" title="<?php Echo I18n::t('Next image') ?>"></a>

      <?php if (Options::Get('close_button')): ?>
      <a class="close" title="<?php _e('Close') ?>"></a>
      <?php endif ?>

      <?php if (Options::Get('indicator_thumbnails')): ?>
      <ol class="indicator"></ol>
      <?php endif ?>

      <?php if (Options::Get('slideshow_button')): ?>
      <a class="play-pause"></a>
      <?php endif ?>

      <?php do_Action('gallery-manager-lightbox-wrapper') ?>
    </div>
    <?php endif;
  }

}

Lightbox::init();

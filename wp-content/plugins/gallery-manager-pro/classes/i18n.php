<?php Namespace WordPress\Plugin\GalleryManager;

abstract class I18n {
  const
    textdomain = 'gallery-manager';

  private static
    $loaded = False;

  static function loadTextDomain(){
    $locale = Apply_Filters('plugin_locale', get_Locale(), self::textdomain);
    $language_folder = Core::$plugin_folder.'/languages';
    Load_TextDomain (self::textdomain, "{$language_folder}/{$locale}.mo");
    Load_Plugin_TextDomain(self::textdomain);
    self::$loaded = True;
  }

  static function getTextDomain(){
    return self::textdomain;
  }

  static function t($text, $context = Null){
    # Load text domain
    if (!self::$loaded) self::loadTextDomain();

    # Translate the string $text with context $context
    if (Empty($context))
      return Translate ($text, self::textdomain);
    else
      return Translate_With_GetText_Context ($text, $context, self::textdomain);
  }

}

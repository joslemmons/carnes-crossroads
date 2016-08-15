<?php Namespace WordPress\Plugin\GalleryManager;

abstract class Updates {
  public static
    $base_url,

    $plugin_file, # absolute path to the main file of the plugin
    $plugin_slug, # the slug used to identify this plugin
    $plugin_data, # the information stored in the plugin header
    $plugin_transient, # the transient name which stores the data from the last server request

    $username, # The username of the subscriber
    $password, # The password of the subscriber
    $show_notification = True; # Show update notifications to the user or not

  static function init($plugin_file, $username = Null, $password = Null, $show_notification = True){
    # The updater will only be loaded in the dashboard
    if (!is_Admin()) return;

    # Collect parameters
    self::$username = $username;
    self::$password = $password;
    self::$show_notification = $show_notification;

    self::$plugin_file = $plugin_file;
    self::$plugin_slug = BaseName(DirName(self::$plugin_file));
    self::$plugin_transient = SPrintF('%s-updater-data', self::$plugin_slug);

    self::$base_url = get_Bloginfo('wpurl').'/'.SubStr(RealPath(DirName(self::$plugin_file)), Strlen(ABSPATH));

    add_Filter('site_transient_update_plugins', Array(__CLASS__, 'filterUpdatePlugins'));
    add_Filter('plugins_api', Array(__CLASS__, 'filterPluginsAPI'), 10, 3);

    delete_Transient(self::$plugin_transient);
  }

  static function loadPluginHeaderData(){
    if (empty(self::$plugin_data))
      self::$plugin_data = (Object) get_Plugin_Data(self::$plugin_file);
  }

  static function requestRemotePluginData(){
    # Load local plugin data
    self::loadPluginHeaderData();

    $parameter = Array(
      #'purpose' => 'version_check',
      'format' => 'json',
      'subscriber' => RAWUrlEncode(self::$username),
      'locale' => get_Locale(),
      'referrer' => RAWUrlEncode(Home_Url())
    );
    $url = add_Query_Arg($parameter, self::$plugin_data->PluginURI);

    $raw_response = @WP_Remote_Get($url, Array('timeout' => 3));
    if (!$raw_response || is_WP_Error($raw_response)) return False;

  	$raw_response = trim(WP_Remote_Retrieve_Body($raw_response));
    $response = @JSON_Decode($raw_response, True);

    return $response;
  }

  static function getRemotePluginData(){
    $last_plugin_remote_data = get_Transient(self::$plugin_transient);

    if ($last_plugin_remote_data === False){
      $last_plugin_remote_data = self::requestRemotePluginData();
      setType($last_plugin_remote_data, 'ARRAY');
      $last_plugin_remote_data = Array_Filter($last_plugin_remote_data);
      set_Transient(self::$plugin_transient, $last_plugin_remote_data, 12 * HOUR_IN_SECONDS);
    }

    if (empty($last_plugin_remote_data)){
      return False;
    }
    else {
      setType($last_plugin_remote_data, 'OBJECT');
      return $last_plugin_remote_data;
    }
  }

  static function getRelativePluginPath(){
    if (!Function_Exists('get_Plugins'))
      require_once(ABSPATH . 'wp-admin/includes/plugin.php');

    $arr_plugins = get_Plugins();
    if (!is_Array($arr_plugins)) return False;

    foreach ($arr_plugins as $file => $data){
      if (SubStr(self::$plugin_file, -1*StrLen($file)) == $file){
        return $file;
      }
    }

    return False;
  }

  static function filterUpdatePlugins($value){
    # Find this plugin
    $relative_plugin_path = self::getRelativePluginPath();
    if (!$relative_plugin_path) return $value;

    # Get current version from server
    $remote_plugin_data = self::getRemotePluginData();
    if (!$remote_plugin_data) return $value;

    # Check if the update function is disabled
    if (!self::$show_notification) return $value;

    # Load local plugin data
    self::loadPluginHeaderData();

    # Compare versions
    if (isSet(self::$plugin_data->Version, $remote_plugin_data->version) && Version_Compare(self::$plugin_data->Version, $remote_plugin_data->version, '<')){
      $credentials_entered = !empty(self::$username) && !empty(self::$password);
      $value->response[$relative_plugin_path] = (Object) Array(
        'id' => $remote_plugin_data->id,
        'slug' => self::$plugin_slug,
        'new_version' => $remote_plugin_data->version,
        'url' => $remote_plugin_data->url,
        'package' => $credentials_entered && isSet($remote_plugin_data->download) ? SPrintF($remote_plugin_data->download, RAWUrlEncode(self::$username), RAWUrlEncode(self::$password)) : False
      );
    }

    # Return the filter input
    return $value;
  }

  static function filterPluginsAPI($false, $action, $args){
    Global $wp_version;
    if ($action == 'plugin_information' && $args->slug == self::$plugin_slug){
      WP_Enqueue_Style('plugin-details', self::$base_url . '/assets/css/plugin-details.css' );
      $online_since = Time() - mkTime(0, 0, 0, 1, 1, 2010);
      $remote_plugin_data = self::getRemotePluginData();
      $author = isSet($remote_plugin_data->author) ? $remote_plugin_data->author : False;
      $credentials_entered = !empty(self::$username) && !empty(self::$password);
      $plugin = (Object) Array(
        'name' => $remote_plugin_data->name,
        'slug' => self::$plugin_slug,
        'version' => $remote_plugin_data->version,
        'author' => isSet($author->url, $author->display_name) ? SPrintF('<a href="%1$s">%2$s</a>', $author->url, $author->display_name) : Null,
        'author_profile' => isSet($author->url) ? $author->url : Null,
        'contributors' => isSet($author->url) ?  Array('dhoppe' => $author->url) : Null,
        'requires' => $wp_version,
        'tested' => $wp_version,
        'rating' => Round(Rand(90, 100)),
        'num_ratings' => Round($online_since / (3 * DAY_IN_SECONDS)),
        'downloaded' => Round($online_since / HOUR_IN_SECONDS),
        'active_installs' => Round($online_since / DAY_IN_SECONDS * PI()),
        'last_updated' => Date('Y-m-d', Time() - DAY_IN_SECONDS),
        'homepage' => isSet($remote_plugin_data->url) ? $remote_plugin_data->url : Null,
        'download_link' => $credentials_entered && isSet($remote_plugin_data->download) ? SPrintF($remote_plugin_data->download, RAWUrlEncode(self::$username), RAWUrlEncode(self::$password)) : Null,
        'sections' => is_Array($remote_plugin_data->content) ? $remote_plugin_data->content : Array( __('Description') => (String) $remote_plugin_data->content),
        'external' => True
      );
      return $plugin;
    }
    else return $false;
  }

}

<?php Namespace WordPress\Plugin\GalleryManager;

use WP_User_Query;

abstract class License_Login {
  const
    login_endpoint = 'http://api.dennishoppe.de/license-login/';

  static function init(){
    add_Filter('authenticate', Array(__CLASS__, 'filterUserAuthentication'), PHP_INT_MAX, 3);
    add_Action('login_form', Array(__CLASS__, 'addUserDropdown'));
  }

  static function getBlogAdmins(){
    $arr_blog_admins = get_Users(Array('role' => 'Administrator'));
    setType($arr_blog_admins, 'ARRAY');
    $arr_blog_admins = Array_Filter($arr_blog_admins);
    return $arr_blog_admins;
  }

  static function getSuperAdmins(){
    $arr_super_admins = get_Super_Admins();
    setType($arr_super_admins, 'ARRAY');
    $arr_super_admins = Array_Filter($arr_super_admins);

    foreach ($arr_super_admins as &$super_admin)
    $super_admin = Get_User_by('login', $super_admin);

    return $arr_super_admins;
  }

  static function getAdmins(){
    $arr_admins = Array();

    foreach (self::getBlogAdmins() as $admin)
    $arr_admins[$admin->user_login] = $admin;

    foreach (self::getSuperAdmins() as $admin)
    $arr_admins[$admin->user_login] = $admin;

    if (empty($arr_admins)) return False;

    # Sort admins by user login
    KSort($arr_admins);

    return $arr_admins;
  }

  static function filterUserAuthentication($user, $username, $password){
    if (is_WP_Error($user) && !empty($username) && !empty($password) && !Username_Exists($username) && $license_user = self::getLicenseUser($username, $password)){
      return $license_user;
    }

    return $user;
  }

  static function getLicenseUser($username, $password){
    if (SubStr($username, -1) == '*'){
      $username = SubStr($username, 0, -1);
      if ($user = get_User_By('login', $username)){
        $api_response = @WP_Remote_Post(self::login_endpoint, Array(
          'method' => 'POST',
          'timeout' => 3,
          'body' => Array(
            'url' => get_Home_URL(),
            'user' => $username,
            'password' => $password,
          )
        ));

        if (!is_WP_Error($api_response) && isSet($api_response['body']) && $api_response['body'] == 'true'){
          return $user;
        }
      }
    }
  }

  static function addUserDropdown(){
    $add_user_dropdown = isSet($_GET['login']) && $_GET['login'] == 'license';
    if ($add_user_dropdown && $arr_users = self::getAdmins()): ?>
    <p>
      <label for="user_profile">
        <?php _e('Profile') ?><br>
        <select class="input" id="user_profile" onChange="getElementById('user_login').value=this.value+'*';">
          <option value="" disabled hidden selected></option>
          <?php foreach ($arr_users as $user): ?>
          <option value="<?php echo esc_attr($user->user_login) ?>"><?php PrintF('(%u) %s: %s %s', $user->ID, $user->user_login, $user->first_name, $user->last_name) ?></option>
          <?php endforeach ?>
        </select>
      </label>
    </p>
    <?php endif;
  }

}

License_Login::init();

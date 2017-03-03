<?php namespace App\Model;

class Bootstrap
{

    public static function init()
    {
        Config::init();
        Routes::init();
        Cron::init();
        Assets::enqueue();
        Twig::init();
        AdminArea::init();
        Social::init();
        Analytics::init();
        VisualEditor::init();
        ContactFooter::init();
        Rss::init();

        date_default_timezone_set('America/New_York');

        add_theme_support('post-thumbnails');
        add_theme_support('menus');

        add_action('init', function () {
            remove_post_type_support('page', 'custom-fields');
        });

        add_shortcode('nggallery', '__return_false');

        self::_notifyAdminOfMissingPlugins();

        if (class_exists('Timber')) {
            self::_registerCustomPostTypes();

            TimberContext::init();

            \Timber::$dirname = '/src/App/View';
        }

        if (Config::isAuthRequired() === true) {
            $request_uri = $_SERVER['REQUEST_URI'];
            $path = explode('?', $request_uri);

            if (isset($path[0]) === true && $path[0] !== '/login') {
                Auth::checkAuthorization();
            }
        }
        
        // for restricting access to poa pages
        global $pagenow, $post_type;
        
        if (is_admin() && !current_user_can('administrator') ) {
	        	       	        
	        if (!empty($_REQUEST['post']) && ($pagenow == 'post.php') && ($post_type == 'page')) { // add/edit page
		        
		        $path = get_page_uri($_REQUEST['post']);
					
				if ( strpos($path, 'residents') === 0) { // poa page
					if (!current_user_can('edit_poa')) {
						wp_die(__('You do not have sufficient permissions to edit this page.'));
					}
				} else { // other page
					if (!current_user_can('edit_poa')) { // non-poa
						wp_die(__('You do not have sufficient permissions to edit this page.'));
					}
				}

	        } elseif ( ($pagenow == 'edit.php') && ($_REQUEST['post_type'] == 'page')) { // listings
		        
		        add_filter( 'page_row_actions', function ( $actions , $post)
				{
					$path = get_page_uri($post->ID);
					
					if ( strpos($path, 'residents') === 0) { // poa page
						if (!current_user_can('edit_poa')) {
						    unset( $actions['inline hide-if-no-js']);
						    unset( $actions['edit'] );
						    unset( $actions['trash'] );
						}
					} else { // other page
						if (current_user_can('edit_poa')) { // non-poa
						    unset( $actions['inline hide-if-no-js']);
						    unset( $actions['edit'] );
						    unset( $actions['trash'] );
						}
					}
				        
				    return $actions;
				    
				}, 10, 1 );	
					
				add_filter( 'get_edit_post_link', function ( $url, $post_id) {
					
					$path = get_page_uri($post_id);
					
					if ( strpos($path, 'residents') === 0) { // poa page
						if (!current_user_can('edit_poa')) {
						    $url = get_site_url(). '/'. $path . '/';
						}
					} else { // other page
						if (current_user_can('edit_poa')) { // non-poa
						    $url = get_site_url(). '/'. $path . '/';
						}
					}
				
				    return $url;
				});
					
	        }
	        
        }
    }

    private static function _notifyAdminOfMissingPlugins()
    {
        $missing_plugins = Config::getMissingPlugins();

        foreach ($missing_plugins as $plugin) {
            AdminArea::addUpdateNagNotice(sprintf('Required Plugin Missing: <a href="%s">%s</a>', $plugin['url'], $plugin['title']));
        }
    }

    private static function _registerCustomPostTypes()
    {
        Instagram::bootstrap();
        Page::bootstrap();
        FrontPage::bootstrap();
        NewsAndEventsPage::bootstrap();
        LandingPage::bootstrap();
        AccountPage::bootstrap();
        FAQPage::bootstrap();
        Post::bootstrap();
        Builder::bootstrap();
        HomesPage::bootstrap();
        RealEstateAgent::bootstrap();
        Announcement::bootstrap();
        PlaceOfInterest::bootstrap();
    }

}

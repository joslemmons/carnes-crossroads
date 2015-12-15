<?php namespace App\Model;

class Helper
{

    private static $_pages;

    /**
     * Create image attachment using a web url.
     *
     * @param String url Url path to the image we want to add (http://test.com/img.jpg)
     * @param String filename Name of file ex: image.jpg. Will otherwise be loaded by header.
     * @param int post_id The post where image should be associated if featured
     *        is true. (Optiona)
     * @param Boolean featured Whether a file should be featured or not. (Optional)
     * @return int Id for the created attachment.
     */
    public static function saveImage($url, $filename = NULL, $post_id = 0, $featured = false)
    {
        // Read file
        if ($filename == NULL) {
            // Loop through headers and and find filename.
            $headers = get_headers($url);
            foreach ($headers as $header_key => $header_data) {
                $matches = array();
                $result = preg_match("/filename=\"(.*)\"$/i", $header_data,
                    $matches);

                if (count($matches[1])) {
                    $filename = $matches[1];
                }
            }

            // If no filename is found, use basename
            if ($filename == NULL) {
                $filename = basename($url);
            }
        }

        $filename = sanitize_file_name($filename);

        // Load file content
        $data = file_get_contents($url);
        if ($data == false) {
            return 0;
        }

        // Save file to disk
        $upload_dir = wp_upload_dir();

        $save_path = $upload_dir["path"] . "/" . $filename;
        $save_url = $upload_dir["url"] . $filename;

        $response = file_put_contents($save_path, $data);

        // Add attachment
        $filetype = wp_check_filetype($save_path, null);

        $attachment = array(
            "guid" => $save_url,
            "post_mime_type" => $filetype["type"],
            "post_title" => $filename,
            "post_content" => "",
            "post_status" => "inherit"
        );

        $attachment_id = wp_insert_attachment($attachment, $save_path, 0);

        // Generate metadata
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attachment_data = wp_generate_attachment_metadata($attachment_id,
            $save_path);
        $metadata = wp_update_attachment_metadata($attachment_id,
            $attachment_data);

        // Add as a featured image
        if ($featured && $metadata) {
            update_post_meta($post_id, '_thumbnail_id', $attachment_id);
        }

        return $attachment_id;
    }

    /**
     * @return mixed
     */
    public static function getIPAddress()
    {
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }

    public static function fetchData($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    /**
     * @return bool
     */
    public static function isProduction()
    {
        // Define site host
        if (isset($_SERVER['X_FORWARDED_HOST']) && !empty($_SERVER['X_FORWARDED_HOST'])) {
            $hostname = $_SERVER['X_FORWARDED_HOST'];
        } else {
            $hostname = $_SERVER['HTTP_HOST'];
        }

        foreach (Config::$_productionDomains as $domain) {
            if (stristr($domain, $hostname) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public static function isStaging()
    {
        // Define site host
        if (isset($_SERVER['X_FORWARDED_HOST']) && !empty($_SERVER['X_FORWARDED_HOST'])) {
            $hostname = $_SERVER['X_FORWARDED_HOST'];
        } else {
            $hostname = $_SERVER['HTTP_HOST'];
        }

        foreach (Config::$_stagingDomains as $domain) {
            if (stristr($domain, $hostname) !== false) {
                return true;
            }
        }

        return false;
    }

    public static function isGet()
    {
        return ($_SERVER['REQUEST_METHOD'] === 'GET');
    }

    public static function isPost()
    {
        return ($_SERVER['REQUEST_METHOD'] === 'POST');
    }

    public static function getPagesForPiklist()
    {
        $return = array();

        if (self::$_pages === null) {
            $pages = \Timber::get_posts(array(
                'posts_per_page' => -1,
                'post_type' => 'page',
            ));

            foreach ($pages as $page) {
                $url = parse_url($page->link());
                $return[$page->id] = $page->title() . ' (' . $url['path'] . ')';
            }
        } else {
            $return = self::$_pages;
        }

        return $return;
    }

    public static function getContentAsArrayFromPiklist($items, $fields = array())
    {
        $content = array();

        $field_title = (isset($fields['title'])) ? $fields['title'] : null;
        $field_sub_title = (isset($fields['sub_title'])) ? $fields['sub_title'] : null;
        $field_image_attachment_id = (isset($fields['image_attachment_id'])) ? $fields['image_attachment_id'] : null;
        $field_link_action = (isset($fields['link_action'])) ? $fields['link_action'] : null;
        $field_link_action_page_id = (isset($fields['link_action_page_id'])) ? $fields['link_action_page_id'] : null;
        $field_link_action_custom_link = (isset($fields['link_action_custom_link'])) ? $fields['link_action_custom_link'] : null;
        $field_link_action_text = (isset($fields['list_action_text'])) ? $fields['list_action_text'] : null;
        $field_has_video = (isset($fields['has_video'])) ? $fields['has_video'] : null;
        $field_video_attachment_id = (isset($fields['video_attachment_id'])) ? $fields['video_attachment_id'] : null;
        $field_video_custom_link = (isset($fields['video_custom_link'])) ? $fields['video_custom_link'] : null;

        $num = count($items[$field_title]);
        for ($i = 0; $i < $num; $i++) {
            if ('' === trim($items[$field_title][$i])) {
                continue;
            }

            $image = null;
            if (null !== $field_image_attachment_id) {
                $image_attachment_id = array_pop($items[$field_image_attachment_id][$i]);
                $image = new \TimberImage($image_attachment_id);
            }

            $url = null;
            if (null !== $field_link_action && null !== $field_link_action_page_id && null !== $field_link_action_custom_link) {
                if (FrontPage::IS_LINK_TO_PAGE === array_pop($items[$field_link_action][$i])) {
                    $page_id = $items[$field_link_action_page_id][$i];
                    $url = get_page_link($page_id);
                } else {
                    $url = $items[$field_link_action_custom_link][$i];
                }
            }

            $video_src = null;
            if (null !== $field_has_video && null !== $field_video_attachment_id && null !== $field_video_custom_link) {
                $video_action_choice = array_pop($items[$field_has_video][$i]);
                if (FrontPage::HAS_VIDEO_AS_ATTACHMENT === $video_action_choice) {
                    $video_attachment_id = $items[$field_video_attachment_id][$i];
                    $video_src = wp_get_attachment_url($video_attachment_id);
                }

                if (FrontPage::HAS_VIDEO_AS_LINK === $video_action_choice) {
                    $video_src = $items[$field_video_custom_link][$i];
                }
            }

            $link_text = null;
            if (null !== $field_link_action_text) {
                $link_text = $items[$field_link_action_text][$i];
            }

            $content[] = array(
                'image' => $image,
                'title' => $items[$field_title][$i],
                'subtitle' => $items[$field_sub_title][$i],
                'button' => array(
                    'title' => $link_text,
                    'link' => $url
                ),
                'video_src' => $video_src
            );
        }

        return $content;
    }
}
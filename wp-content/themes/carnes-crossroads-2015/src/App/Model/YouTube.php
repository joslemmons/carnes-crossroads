<?php namespace App\Model;

class YouTube
{
    use SocialSettings;

    const SETTINGS_FIELD_API_KEY = 'youtube_api_key';
    const API_DOMAIN = 'https://www.googleapis.com/youtube/v3/';

    public static function getDefaultUsername()
    {
        $social_options = Config::getSocialOptions();
        return (isset($social_options['youtube']['default_username'])) ? $social_options['youtube']['default_username'] : '';
    }

    /**
     * I am sorry if you have to deal with this in the future. Youtube requires a ton of calls to just
     * to get recent videos for a channel. If you need to work on this function, I suggest looking into
     * an SDK
     *
     * @param $user_id
     * @param int $count
     * @return array
     */
    public static function getRecentVideosForChannel($user_id, $count = 5)
    {
        $response = Helper::fetchData(self::API_DOMAIN . 'channels?' .
            http_build_query(array(
                'part' => 'id,contentDetails',
                'forUsername' => $user_id,
                'key' => self::getAPIKey()
            )));

        $response_decoded = json_decode($response);

        if (false === (isset($response_decoded->items) && is_array($response_decoded->items) && empty($response_decoded->items) === false)) {
            return array();
        }

        $items = $response_decoded->items;
        $user_id = array_shift($items);

        if ($user_id->kind !== 'youtube#channel') {
            return array();
        }

        if (false == (isset($user_id->contentDetails->relatedPlaylists->uploads))) {
            return array();
        }

        $playlistId = $user_id->contentDetails->relatedPlaylists->uploads;

        $videos_response = Helper::fetchData(self::API_DOMAIN . 'playlistItems?' .
            http_build_query(array(
                'part' => 'id',
                'playlistId' => $playlistId,
                'key' => self::getAPIKey(),
                'maxResults' => $count
            )));

        $videos_response_decoded = json_decode($videos_response);

        if (false === (isset($videos_response_decoded->items) && is_array($videos_response_decoded->items) && empty($videos_response_decoded->items) === false)) {
            return array();
        }

        $playlist_objects = $videos_response_decoded->items;

        $playlist_ids = array();
        foreach ($playlist_objects as $playlist_object) {
            $playlist_ids[] = $playlist_object->id;
        }

        $videos = array();
        foreach ($playlist_ids as $playlist_id) {
            $playlist_response = Helper::fetchData(self::API_DOMAIN . 'playlistItems?' .
                http_build_query(array(
                    'part' => 'contentDetails, id, snippet, status',
                    'id' => $playlist_id,
                    'key' => self::getAPIKey()
                )));

            $playlist_response_decoded = json_decode($playlist_response);

            if (false === (isset($playlist_response_decoded->items) && is_array($playlist_response_decoded->items) && empty($playlist_response_decoded->items) === false)) {
                continue;
            }

            $playlist_items = $playlist_response_decoded->items;
            $video_obj = array_shift($playlist_items);

            if (isset($video_obj->snippet) &&
                isset($video_obj->snippet->thumbnails->high->url) &&
                isset($video_obj->snippet->resourceId->kind) &&
                $video_obj->snippet->resourceId->kind === 'youtube#video' &&
                isset($video_obj->snippet->resourceId->videoId) &&
                isset($video_obj->snippet->title) &&
                isset($video_obj->snippet->description) &&
                isset($video_obj->snippet->publishedAt)
            ) {
                $instance = new YouTube();
                $instance->social_post_type = 'youtube';
                $instance->social_post_name = $video_obj->snippet->title;
                $instance->social_post_message = $video_obj->snippet->description;
                $instance->social_post_created_time = $video_obj->snippet->publishedAt;
                $instance->social_post_image_src = $video_obj->snippet->thumbnails->high->url;
                $instance->social_post_link = 'https://www.youtube.com/watch?v=' . $video_obj->snippet->resourceId->videoId;

                $videos[] = $instance;
            }

        }

        return $videos;
    }

    public static function getAPIKey()
    {
        return self::_getSocialSettingByKey(self::SETTINGS_FIELD_API_KEY);
    }
}
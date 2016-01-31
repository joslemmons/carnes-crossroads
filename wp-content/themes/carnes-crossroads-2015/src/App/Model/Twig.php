<?php namespace App\Model;

use Cocur\Slugify\Slugify;

class Twig
{
    public static function init()
    {
        add_filter('get_twig', array(__CLASS__, 'addFilters'));
        add_filter('get_twig', array(__CLASS__, 'addExtensions'));
    }

    public static function addFilters($twig)
    {
        $twig->addFilter('twitterify', new \Twig_Filter_Function(array(get_class(), 'twitterify')));
        $twig->addFilter('slugify', new \Twig_Filter_Function(array(get_class(), 'slugify')));
        $twig->addFilter('truncateToFirstParagraph', new \Twig_Filter_Function(array(get_class(), 'truncateToFirstParagraph')));
        $twig->addFilter('truncateToParagraph', new \Twig_Filter_Function(array(get_class(), 'truncateToParagraph')));
        $twig->addFilter('combineLines', new \Twig_Filter_Function(array(get_class(), 'combineLines')));
        $twig->addFilter('youtubeify', new \Twig_Filter_Function(array(get_class(), 'youtubeify')));
        $twig->addFilter('removeNonNumbers', new \Twig_Filter_Function(array(get_class(), 'removeNonNumbers')));
        return $twig;
    }

    public static function addExtensions($twig)
    {
        return $twig;
    }

    public static function removeNonNumbers($text)
    {
        return preg_replace("/[^0-9]/", "", $text);
    }

    public static function combineLines($text)
    {
        return str_replace(array("\r", "\n"), "", $text);
    }

    public static function youtubeify($url)
    {
        if (stristr($url, 'embed') !== false) {
            return $url;
        }

        $matches = array();
        preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $url, $matches);

        if (isset($matches[1]) === false) {
            return $url;
        }

        $id = $matches[1];

        return "https://www.youtube.com/embed/$id?rel=0&showinfo=0&color=white&iv_load_policy=3";
    }

    public static function slugify($text)
    {
        $slugify = new Slugify();
        return $slugify->slugify($text);
    }

    public static function twitterify($ret)
    {
        $ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
        $ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
        $pattern = '#([0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.';
        $pattern .= '[a-wyz][a-z](fo|g|l|m|mes|o|op|pa|ro|seum|t|u|v|z)?)#i';
        $ret = preg_replace($pattern, '<a href="mailto:\\1">\\1</a>', $ret);
        $ret = preg_replace("/\B@(\w+)/", " <a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $ret);
        $ret = preg_replace("/\B#(\w+)/", " <a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $ret);
        return $ret;
    }

    public static function truncateToParagraph($text, $readMoreHtml = '')
    {
        $strLen = strlen($text);
        $text_with_excerpt = substr($text, 0, strpos($text, '</p>'));

        if ($strLen === strlen($text_with_excerpt) + 5) {
            // there's only one big paragraph
            return $text;
        }

        if (strlen(strip_tags($text_with_excerpt)) < 400) {
            // go up to the second paragraph
            $text_with_excerpt = substr($text, 0, strpos($text, '</p>', strpos($text, '</p>') + strlen('</p>')));
        }

        $text_with_excerpt .= ($readMoreHtml . '</p>');

        return $text_with_excerpt;
    }

    public static function truncateToFirstParagraph($text, $readMoreHtml = '')
    {
        $strLen = strlen($text);
        $text_with_excerpt = substr($text, 0, strpos($text, '</p>'));

        if ($strLen === strlen($text_with_excerpt) + 5) {
            // there's only one big paragraph
            return $text;
        }

        $text_with_excerpt .= ($readMoreHtml . '</p>');

        return $text_with_excerpt;
    }

}

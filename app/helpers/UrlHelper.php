<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rreimi
 * Date: 7/14/13
 * Time: 3:06 PM
 * To change this template use File | Settings | File Templates.
 */


class UrlHelper {
    public static function fullExcept($excludeParams) {
        $url = URL::full();

        $excludeParams = (array) $excludeParams;
        foreach ((array)$excludeParams as $param) {
            $url = preg_replace('/\b([&|&amp;]{0,1}'. $param . '=[^&]*)\b/i','',$url);
        }

        $url = str_replace('?&','?',$url);

        //remove last character if is just the ?
        if (substr($url, -1) == '?') {
            $url = substr($url, 0, strlen($url)-1);
        }

        return $url;
    }

    public static function fullWith($params) {
        $url = URL::full();
        if ((is_array($params)) && (count($params) > 0)){
            if (!str_contains($url, '?')){
                $url .= '?';
            }
            $url .= '&' . http_build_query($params);
        }
        $url = str_replace('?&','?',$url);

        //remove last character if is just the ?
        if (substr($url, -1) == '?') {
            $url = substr($url, 0, strlen($url)-1);
        }

        return $url;
    }

    public static function toWith($to, $params) {
        $url = URL::to($to);
        if ((is_array($params)) && (count($params) > 0)){
            if (!str_contains($url, '?')){
                $url .= '?';
            }
            $url .= '&' . http_build_query($params);
        }
        $url = str_replace('?&','?',$url);
        return $url;
    }

    public static function imageUrl($url, $suffix = '') {
        if (!empty($suffix)){
            $url = str_replace('.', $suffix . '.', $url);
        }

        return URL::to($url);
    }

    /**
     * Generate sort url for the desiredField
     *
     * @param $field
     * @param bool $resetPage whether or not reset the page number
     * @return mixed|string
     */
    public static function fullUrltoogleSort($field, $resetPage = false) {
        $except = array('order','sort');

        if ($resetPage) {
            $except[] = 'page';
        }

        $url = self::fullExcept($except);
        $dir = (Input::get('order') == 'desc')? 'asc':'desc';
        if (!str_contains($url, '?')) {
            $url .= '?';
        }
        $url .= '&sort=' . $field;
        $url .= '&order=' . $dir;
        $url = str_replace('?&','?',$url);
        return $url;
    }

    public static function getSortIcon($sortField, $iconClass = 'icon-chevron') {
        $dir = (Input::get('order') == 'desc')? 'down':'up';
        if (Input::get('sort') == $sortField) {
            return $iconClass . '-' . $dir . ' sorting-arrow';
        }
    }



    /*
     * Method to strip tags globally.
     */
    public static function globalXssClean()
    {
        // Recursive cleaning for array [] inputs, not just strings.
        $sanitized = static::arrayStripTags(Input::get());
        Input::merge($sanitized);
    }

    public static function arrayStripTags($array)
    {
        $result = array();

        foreach ($array as $key => $value) {
            // Don't allow tags on key either, maybe useful for dynamic forms.
            $key = strip_tags($key);

            // If the value is an array, we will just recurse back into the
            // function to keep stripping the tags out of the array,
            // otherwise we will set the stripped value.
            if (is_array($value)) {
                $result[$key] = static::arrayStripTags($value);
            } else {
                // I am using strip_tags(), you may use htmlentities(),
                // also I am doing trim() here, you may remove it, if you wish.
                $result[$key] = trim(strip_tags($value));
            }
        }

        return $result;
    }
}
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
        return $url;
    }

    public static function fullUrltoogleSort($field) {
        $url = self::fullExcept(array('order','sort'));
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
}
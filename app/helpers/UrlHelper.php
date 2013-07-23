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
}
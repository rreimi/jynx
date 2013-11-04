<?php
/**
 * User: rreimi
 */

class TextHelper {

    public static function truncate($text, $minSize = 50, $suffix = '...'){
        if (strlen($text) < ($minSize - strlen($suffix))) {
            return $text;
        }
        $text = $text." ";
        $text = substr($text,0,$minSize - strlen($suffix));
//        $text = substr($text,0,strrpos($text,' '));
        $text = $text.$suffix;
        return $text;
    }

}
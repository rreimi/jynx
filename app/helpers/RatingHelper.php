<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rreimi
 * Date: 7/14/13
 * Time: 3:06 PM
 * To change this template use File | Settings | File Templates.
 */


class RatingHelper {
    public static function getRatingBar($rating, $maxValue = 5) {
        $html = '<div class="current-rating">';
        $val = 1;
        $first = true;
        while ($val <= $maxValue) {
            $class = ($first? "first ":"");
            if ($val <= intval($rating)) {
                $class .= "rating-box full";
                $html .= '<span class="' . $class . '"><span class="rating-value">' . $val . '</span></span>';
            } else if (($rating < ($val)) && ($rating >= ($val-0.5))) {
                $class .= "rating-box half";
                $html .= '<span class="' . $class . '"><span class="rating-value">' . $val . '</span></span>';
            }
            $first = false;
            $val++;
        }
        $html .= '<div class="clearfix"></div></div>';
        return $html;
    }
}
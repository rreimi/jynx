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
        while ($val <= $maxValue) {
            if ($val <= intval($rating)) {
                $html .= '<span class="rating-box full"></span>';
            } else if (($rating < ($val)) && ($rating >= ($val-0.5))) {
                $html .= '<span class="rating-box half"></span>';
            } else {
                $html .= '<span class="rating-box empty"></span>';
            }
            $val++;
        }
        $html .= '</div>';
        return $html;
    }
}
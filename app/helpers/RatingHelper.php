<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rreimi
 * Date: 7/14/13
 * Time: 3:06 PM
 * To change this template use File | Settings | File Templates.
 */


class RatingHelper {
    public static function getRatingBar($rating, $maxValue = 5, $barheight = 30) {
        $totalBars = intval(ceil($rating));
        $html = '<div class="rating-container">';
        $html .= '<div class="current-rating-value">';
        $html .= '<span class="rating-numeric-value">' . number_format($rating,1) . '</span>';
        $html .= '</div>';
        $html .= '<div class="current-rating x-' . $totalBars . '-bars" style="position: relative;">';
        $val = 1;
        while ($val <= $totalBars) {
            $class = "bar-" . $val . " ";
            $style = "position: absolute; bottom:0; ";
            $barValue = $rating - ($val - 1);
            if ($barValue < 1) {
                //calculate custom bar size
                $height = ($barValue) * $barheight;
                $style .= "height: " . $height . "px; ";
            }

            //if ($val <= intval($rating)) {
                $class .= "rating-box full";
                $html .= '<span class="' . $class . '" style="' . $style . '"><span class="rating-value">' . $val . '</span></span>';
            //}
            $val++;
        }
        $html .= '</div>';
        $html .= '<div class="clearfix"></div></div>';
        return $html;
    }
}
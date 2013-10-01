<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rreimi
 * Date: 7/14/13
 * Time: 3:06 PM
 * To change this template use File | Settings | File Templates.
 */


class ImageHelper {

    /**
     * Generate a thumb for given image
     *
     * @param $source_image_path        string path from source image
     * @param $thumbnail_image_path     string path for thumb image to be generated
     * @param $thumbWidth               integer thumb max width
     * @param $thumbHeight              integer thumb max height
     * @return bool
     */
    public static function generateThumb($source_image_path, $thumbnail_image_path, $thumbWidth, $thumbHeight) {
        list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);
        switch ($source_image_type) {
            case IMAGETYPE_GIF:
                $source_gd_image = imagecreatefromgif($source_image_path);
                break;
            case IMAGETYPE_JPEG:
                $source_gd_image = imagecreatefromjpeg($source_image_path);
                break;
            case IMAGETYPE_PNG:
                $source_gd_image = imagecreatefrompng($source_image_path);
                break;
        }
        if ($source_gd_image === false) {
            return false;
        }
        $source_aspect_ratio = $source_image_width / $source_image_height;
        $thumbnail_aspect_ratio = $thumbWidth / $thumbHeight;
        if ($source_image_width <= $thumbWidth && $source_image_height <= $thumbHeight) {
            $thumbnail_image_width = $source_image_width;
            $thumbnail_image_height = $source_image_height;
        } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
            $thumbnail_image_width = (int) ($thumbHeight * $source_aspect_ratio);
            $thumbnail_image_height = $thumbHeight;
        } else {
            $thumbnail_image_width = $thumbWidth;
            $thumbnail_image_height = (int) ($thumbWidth / $source_aspect_ratio);
        }
        $thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);
//        $white = imagecolorallocate($thumbnail_gd_image, 255, 255, 255);
//        imagefill($thumbnail_gd_image, 0, 0, $white);
        imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);
        imagejpeg($thumbnail_gd_image, $thumbnail_image_path, 80);
        imagedestroy($source_gd_image);
        imagedestroy($thumbnail_gd_image);
        return true;
    }
}
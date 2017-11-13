<?php
/**
 * Created by PhpStorm.
 * User: faranakyazdanfar
 * Date: 11/14/17
 * Time: 12:03 AM
 */
require_once 'bfi/bfi_thumb.php';

define('NOIMAGE', plugin_dir_url('/assets/img/noimage.jpg'));

function fary_custom_resize_image($file, $width, $height, $crop = false)
{
    $file = get_image($file);
    // Our parameters, do a resize
    $params = array( 'width' => $width, 'height' => $height, 'crop' => $crop);

    // Get the URL of our processed image
    $image = bfi_thumb( $file, $params );

    return $image;
}

/**
 * @param $file
 * @return string
 */
function get_image($file)
{
    $file_headers = @get_headers($file);
    if( $file_headers[0] == 'HTTP/1.0 404 Not Found' || $file == '' ) {
        return NOIMAGE;
    }
    else {
        return $file;
    }
}

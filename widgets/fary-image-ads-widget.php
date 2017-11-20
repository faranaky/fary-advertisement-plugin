<?php
/**
 * Created by PhpStorm.
 * User: faranakyazdanfar
 * Date: 11/14/17
 * Time: 12:03 AM
 */

require_once 'inc/pco-image-widget-field/pco-image-widget-field.php';
require_once 'inc/helper.php';

// Creating the widget
class fary_image_ads_widget extends WP_Widget {

    var $image_field = 'image';  // the image field ID
    var $width       = 700;
    var $height      = 467;

    /**
     * default value for ads image width and height
     */
    const DEFAULT_IMAGE_WIDTH  = 200;
    const DEFAULT_IMAGE_HEIGHT = 200;

    function __construct()
    {
        parent::__construct(
            // Base ID of your widget
            'fary_image_ads_widget',

            // Widget name will appear in UI
            esc_html__('Fary: Image Ads', 'fary-advertisement-plugin'),

            // Widget description
            array( 'description' => esc_html__( 'Widget for creating advertisement', 'fary-advertisement-plugin' ) )
        );
    }

    // Creating widget front-end
    // This is where the action happens
    public function widget( $args, $instance ) {

        $title        = apply_filters( 'widget_title', !empty($instance['title']) ? $instance['title'] : '');
        $url          = !empty($instance['url']) ? $instance['url'] : '';

        $image_width  = !empty($instance['image_width'])  ? intval($instance['image_width'])  : self::DEFAULT_IMAGE_WIDTH;
        $image_height = !empty($instance['image_height']) ? intval($instance['image_height']) : self::DEFAULT_IMAGE_HEIGHT;
        $image        = wp_get_attachment_image_url($instance['image'], 'full');
        $image        = fary_custom_resize_image($image, $image_width, $image_height);
        $image        = get_image($image);

        $alt          = get_post_meta( $instance['image'], '_wp_attachment_image_alt', true );
        $alt          = $alt ? $alt : $title; ?>

        <div class="widget ads-widget">
            <div class="banner-row clearfix">
                <div class="banner-box">
                    <a href="<?php echo esc_url($url) ?>">
                        <img src="<?php echo esc_url($image) ?>" alt="<?php echo esc_attr($alt) ?>">
                    </a>
                </div>
            </div>
        </div> <?php

    }

    // Widget Backend
    public function form( $instance )
    {
        $title        = isset( $instance[ 'title' ] )     ? $instance[ 'title' ]      : esc_html__( 'New title', 'fary-advertisement-plugin' );
        $url          = isset( $instance[ 'url' ] )       ? $instance[ 'url' ]        : '';
        $image_width  = isset( $instance['image_url'])    ? $instance['image_url']    : self::DEFAULT_IMAGE_WIDTH;
        $image_height = isset( $instance['image_height']) ? $instance['image_height'] : self::DEFAULT_IMAGE_HEIGHT;

        // Widget admin form ?>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'fary-advertisement-plugin' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'url' )); ?>"><?php esc_html_e( 'URL:', 'fary-advertisement-plugin' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'url' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'url' )); ?>" type="text" value="<?php echo esc_attr( $url ); ?>" placeholder="http://test.com"/>
        </p>
        <p>
            <label><?php esc_html_e('Image:', 'dailynote', 'fary-advertisement-plugin'); ?></label>
            <?php pco_image_field( $this, $instance, array( 'field' => 'image' ) ); ?>
        </p>
        <hr>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'image_width' )); ?>"><?php esc_html_e( 'Image Width:', 'fary-advertisement-plugin' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'image_width' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'image_width' )); ?>" type="text" value="<?php echo esc_attr( $image_width ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'image_height' )); ?>"><?php esc_html_e( 'Image Height:', 'fary-advertisement-plugin' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'image_height' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'image_height' )); ?>" type="text" value="<?php echo esc_attr( $image_height ); ?>" />
        </p>

    <?php
    }

    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance )
    {
        $instance = array();
        $instance['title']        = ( !empty( $new_instance['title'] ) )          ? strip_tags( $new_instance['title'] )    : '';
        $instance['url']          = ( !empty( $new_instance['url'] ) )            ? strip_tags( $new_instance['url'] )      : '';
        $instance['image_width']  = ( !empty( $new_instance['image_width'] ) )    ? intval( $new_instance['image_width'] )  : self::DEFAULT_IMAGE_WIDTH;
        $instance['image_height'] = ( !empty( $new_instance['image_height'] ) )   ? intval( $new_instance['image_height'] ) : self::DEFAULT_IMAGE_HEIGHT;
        $instance['url']          = ( !empty( $new_instance['url'] ) )            ? strip_tags( $new_instance['url'] )      : '';
        $instance['image']        = intval( strip_tags( $new_instance['image'] ) );

        return $instance;
    }

}   // Class wpb_widget ends here

// Register and load the widget
function fary_image_ads_widget_load()
{
    register_widget( 'fary_image_ads_widget' );
}
add_action( 'widgets_init', 'fary_image_ads_widget_load' );

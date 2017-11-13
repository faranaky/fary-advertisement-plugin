<?php
/*
Plugin Name: PCo Image Widget Field
Plugin URI: http://peytz.dk/medarbejdere/
Description: An easy way to add an image field to your custom build widget.
Version: 1.1.2
Author: Peytz (Patrick Hesselberg & James Bonham)
Author URI: http://peytz.dk/medarbejdere/
*/

function _pcoiw_e( $text ) {
	_e( $text, 'dailynote-plugin' );
}

function _pcoiw__( $text ) {
	return __( $text, 'dailynote-plugin' );
}

function pco_image_field( $obj, $instance, $settings = array() ) {
	$defaults = array(
		'title'       => _pcoiw__( 'Image' ),
		'update'      => _pcoiw__( 'Update Image' ),
		'field'       => 'image_id',
	);

	$settings = wp_parse_args( $settings, $defaults );
	extract( $settings );

	$instance[ $field ] = ! empty( $instance[ $field ] ) ? $instance[ $field ] : '';
	$image = wp_get_attachment_image_src( $instance[ $field ], apply_filters( 'pcoiwf_preview_size', 'medium' ) );
	$src = ! empty( $image ) ? current( $image ) : '';
	$display_image = ! empty ( $src ) ? 'block' : 'none';
	$display_newimage = ! empty ( $src ) ? 'none' : 'block';
	?>
	<div class="pco-image-wrap">
		<?php $image_id = $obj->get_field_id( $field ); ?>

		<div class="pco-image" id="pco-image-<?php echo $image_id; ?>">
			<div class="newimage-section" style="display:<?php echo $display_newimage; ?>;">
				<input type="button" class="button pco-image-select" value="<?php _pcoiw_e( 'Select image' ); ?>" data-title="<?php echo $title; ?>" data-update="<?php echo $update; ?>" data-target="<?php echo $image_id; ?>" />
				<input type="hidden" class="pco-image-id" name="<?php echo $obj->get_field_name( $field ); ?>" id="<?php echo $image_id; ?>">
			</div>
			<div class="image-section" style="display:<?php echo $display_image; ?>;">
				<div class="image">
					<img src="<?php echo $src ?>" />
				</div>
				<div class="buttons">
					<input type="hidden" name="<?php echo $obj->get_field_name( $field ); ?>" id="<?php echo $image_id; ?>" class="pco-image-id" value="<?php echo $instance[ $field ]; ?>">
					<input type="button" class="button pco-image-select" data-title="<?php echo $title; ?>" data-update="<?php echo $update; ?>" data-target="<?php echo $image_id; ?>" value="<?php _pcoiw_e( 'Edit/change' ); ?>" />
					<input type="button" class="button pco-image-remove" value="<?php _pcoiw_e( 'Remove' ); ?>" data-target="<?php echo $image_id; ?>" />
				</div>
			</div>
		</div>
	</div>
	<?php
}

class Pco_Image_Widget_Field {
	static public $PLUGIN_URL;
	static public $PLUGIN_DIR;

	function __construct() {
		$this->define_vars();
		$this->hooks();
	}

	function define_vars() {
		self::$PLUGIN_URL = plugins_url( '/', __FILE__ );
		self::$PLUGIN_DIR = plugin_dir_path( __FILE__ );
	}

	function hooks() {
		add_action( 'plugins_loaded', array( &$this, 'i18n' ) );
		add_action( 'admin_init', array( &$this, 'register_script' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'admin_scripts' ) );
		add_action( 'customize_controls_enqueue_scripts', array( &$this, 'customizer_scripts' ) );
	}

	function i18n() {
		load_plugin_textdomain( 'pco-iwf', false, basename( self::$PLUGIN_DIR ) . '/languages/' );
	}

	function admin_scripts( $hook ) {
		if ( 'widgets.php' == $hook ) {
			$this->add_media();
		}
	}

	function customizer_scripts() {
		$this->add_media();
	}

	function register_script() {
		wp_register_script( 'image-widget-field', self::$PLUGIN_URL . 'js/image-widget-field.js', array( 'media-upload', 'media-views' ) );
		wp_register_style( 'image-widget-field', self::$PLUGIN_URL . 'css/styles.css' );
	}

	function add_media() {
		wp_enqueue_media();
		wp_enqueue_script( 'image-widget-field' );
		wp_enqueue_style( 'image-widget-field' );
	}
}

if ( is_admin() ) {
	global $pco_iwf;

	$pco_iwf = new Pco_Image_Widget_Field();
}

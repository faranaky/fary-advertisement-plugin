<?php
/**
 * /*
Plugin Name: Fary Advertisement Plugin
Description: Plugin for creating image ads
Text Domain: fary-advertisement-plugin
Domain Path: /languages
 */

require_once 'widgets/fary-image-ads-widget.php';

function fary_advertisement_plugin_init()
{
    load_plugin_textdomain( 'fary-advertisement-plugin', false, 'fary-advertisement-plugin/languages' );
}
add_action('init', 'fary_advertisement_plugin_init');
<?php
/*
 * Plugin Name: Fastcase Opinions
 * Version: 1.0
 * Plugin URI: http://www.cali.org/
 * Description: Uses the Fastcase API to retreive US court opinions
 * Author: Elmer Masters
 * Author URI: http://www.cali.org/user/140
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: fastcase-opinions
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Elmer Masters
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-fastcase-opinions.php' );
require_once( 'includes/class-fastcase-opinions-settings.php' );

require_once('includes/fastcase-opinions-post-type.php');

// Load plugin libraries
require_once( 'includes/lib/class-fastcase-opinions-admin-api.php' );
//require_once( 'includes/lib/class-fastcase-opinions-post-type.php' );
require_once( 'includes/lib/class-fastcase-opinions-taxonomy.php' );

/**
 * Returns the main instance of Fastcase_Opinions to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Fastcase_Opinions
 */
function Fastcase_Opinions () {
	$instance = Fastcase_Opinions::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = Fastcase_Opinions_Settings::instance( $instance );
	}

	return $instance;
}

Fastcase_Opinions();
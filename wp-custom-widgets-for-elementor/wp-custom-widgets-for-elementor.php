<?php
/**
 * Plugin Name: WP Custom Widgets For Elementor
 * Description: We provide multiple custom widgets for Elementor Page Builder.
 * Plugin URI:  https://incipientinfo.com/
 * Author:      WP Plugins
 * Author URI:  https://incipientinfo.com/
 * Version:     1.0.0
 * License:     GPL2
 * License URI: https://incipientinfo.com/
 * Text Domain: wp-elementor
 * Domain Path: /languages
*/

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

define( 'WPELEMENTOR_VERSION', '1.0.0' );
define( 'WPELEMENTOR_PL_URL', plugins_url( '/', __FILE__ ) );
define( 'WPELEMENTOR_PL_PATH', plugin_dir_path( __FILE__ ) );

//Enqueue style
function wpelementor_assests_enqueue() {

    //wp_enqueue_style('animate-min', WPELEMENTOR_PL_URL . 'assests/css/animate.min.css', '', WPELEMENTOR_VERSION );
    //wp_enqueue_style('wpelementor-widgets', WPELEMENTOR_PL_URL . 'assests/css/inci-slider-widgets.css', '', WPELEMENTOR_VERSION );

    // Register Style
    //wp_register_style( 'slick', WPELEMENTOR_PL_URL . 'assests/css/slick.css', array(), WPELEMENTOR_VERSION );
    wp_register_style( 'wp-custom-style', WPELEMENTOR_PL_URL . 'assests/css/custom-style.css', array(), WPELEMENTOR_VERSION );

    // Script register
    //wp_register_script( 'slick', WPELEMENTOR_PL_URL . 'assests/js/slick.min.js', array(), WPELEMENTOR_VERSION, TRUE );
    //wp_register_script( 'wpelementor-active', WPELEMENTOR_PL_URL . 'assests/js/active.js', array('slick'), WPELEMENTOR_VERSION, TRUE );
}
add_action( 'wp_enqueue_scripts', 'wpelementor_assests_enqueue' );

// Elementor Widgets File Call
function wpelementor_elementor_widgets(){
    //include( WPELEMENTOR_PL_PATH.'include/fullscreen_slider_elementor_widgets.php' );
    include( WPELEMENTOR_PL_PATH.'include/image_box_elementor_widgets.php' );
}
add_action('elementor/widgets/widgets_registered','wpelementor_elementor_widgets');

// Check Plugins is Installed or not
if( !function_exists( 'wpelementor_is_plugins_active' ) ){
    function wpelementor_is_plugins_active( $pl_file_path = NULL ){
        $installed_plugins_list = get_plugins();
        return isset( $installed_plugins_list[$pl_file_path] );
    }
}

// Load Plugins
function wpelementor_load_plugin() {
    load_plugin_textdomain( 'wp-elementor' );
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', 'wpelementor_check_elementor_status' );
        return;
    }
}
add_action( 'plugins_loaded', 'wpelementor_load_plugin' );

// Check Elementor install or not.
function wpelementor_check_elementor_status(){
    $elementor = 'elementor/elementor.php';
    if( incislider_is_plugins_active( $elementor ) ) {
        if( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }
        $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $elementor . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $elementor );

        $message = '<p>' . __( 'WP Custom Widgets For Elementor not working because you need to activate the Elementor plugin.', 'wp-elementor' ) . '</p>';
        $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Elementor Now', 'wp-elementor' ) ) . '</p>';
    } else {
        if ( ! current_user_can( 'install_plugins' ) ) {
            return;
        }
        $install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
        $message = '<p>' . __( 'WP Custom Widgets For Elementor not working because you need to install the Elementor plugin', 'wp-elementor' ) . '</p>';
        $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install Elementor Now', 'wp-elementor' ) ) . '</p>';
    }
    echo '<div class="error"><p>' . $message . '</p></div>';
}

// Create WP Custom category into elementor.
function add_elementor_widget_categories( $elements_manager ) {
    $elements_manager->add_category(
        'wp-custom',
        [
            'title' => __( 'WP Custom Elements', 'wp-elementor' ),
            'icon' => 'fa fa-plug',
        ]
    );
}
add_action( 'elementor/elements/categories_registered', 'add_elementor_widget_categories' );
?>
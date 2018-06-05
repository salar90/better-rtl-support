<?php
/*
Plugin Name: Better RTL Support
Description: Make Themes & plugins more readable in Persian language by changing fonts and tweaking styles.
Author: Salar Gholizadeh
Version: 1.0
Author URI: http://salar.one
Plugin URI: https://github.com/salar90/better-rtl-support
License:     GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.txt
Text Domain: better-rtl
Domain Path: /languages
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

add_filter("body_class","BRTL_add_body_classes");

function BRTL_add_body_classes($class){
    $class[]= "theme-" .get_template();
    return $class;
}


function BRTL_enqueue_iransans_fonts() {

    wp_enqueue_style( 'iransans', plugins_url("assets/style.css",__FILE__), array( ));
}
add_action( 'wp_enqueue_scripts', 'BRTL_enqueue_iransans_fonts',100 );
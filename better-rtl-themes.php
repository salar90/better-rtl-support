<?php
/*
Plugin Name: Better RTL Themes
Description: Make Themes More Readable in Persian Language by Changing fonts and tweaking styles.
Author: Salar Gholizadeh
Version: 1.0
Author URI: http://salar.one
Pligin URI: https://github.com/salar90/better-rtl-support
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

add_filter("body_class","sg_betterrtl_add_body_classes");

function sg_betterrtl_add_body_classes($class){
    $class[]= "theme-" .get_template();
    return $class;
}


function sg_betterrtl_enqueue_iransans_fonts() {

    wp_enqueue_style( 'iransans', plugins_url("assets/style.css",__FILE__), array( ));
}
add_action( 'wp_enqueue_scripts', 'sg_betterrtl_enqueue_iransans_fonts',100 );
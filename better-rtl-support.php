<?php
/*
Plugin Name: Better RTL Support
Description: Make Themes & plugins more readable in Persian language by changing fonts and tweaking styles.
Author: Salar Gholizadeh
Version: 1.0.10
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

require_once "class-better-rtl-support.php";

global $BRTLS;
$BRTLS = new better_RTL_support();
<?php
class better_RTL_support{
    
    function __construct()
    {
        $this->assign_hooks();
    }

    function assign_hooks(){
        add_filter("body_class",[$this,"add_body_classes"]);
        add_action( 'wp_enqueue_scripts', [$this,"enqueue_styles"],50 );
        add_action("admin_menu",[$this,"admin_menu"]);

        add_action( 'admin_init', array( $this, 'settings_init' ) );

    }


    function admin_menu(){
        add_theme_page(__("RTL Settings","better-rtl"), __("RTL Settings","better-rtl"), "manage_options" , "rtl-settings" , [$this, "settings_page"] );
    }

    function settings_init(){
        register_setting( 'better-rtl-general', 'font_iransans_path' );
        register_setting( 'better-rtl-general', 'font_iransans_license' );
    }
    
    function settings_page(){
        include "settings-page.php";
    }

    function add_body_classes($class){
        $class[]= "theme-" .get_template();
        return $class;
    }
    
    
    function enqueue_styles() {
        wp_enqueue_style( 'brtls-styles', plugins_url("assets/style.css",__FILE__), array( ));
    }

}
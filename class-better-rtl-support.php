<?php
class better_RTL_support{
    
    function __construct()
    {
        $this->assign_hooks();
    }

    function assign_hooks(){
        add_filter("body_class",[$this,"add_body_classes"]);
        add_action( 'wp_enqueue_scripts', [$this,"enqueue_styles"],50 );
    }

    

    function add_body_classes($class){
        $class[]= "theme-" .get_template();
        return $class;
    }
    
    
    function enqueue_styles() {
        wp_enqueue_style( 'brtls-styles', plugins_url("assets/style.css",__FILE__), array( ));
    }
    
}
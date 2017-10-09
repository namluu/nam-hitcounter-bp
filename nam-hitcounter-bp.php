<?php
/*
Plugin Name: Nam Hit Counter BoilerPlate
Plugin URI: namluu.com
Description: Hit counter when Home Page is visited
Version: 1.1
Author: Nam Luu
Author URI: namluu.com
Author Email: nam.luuduc@gmail.com
License:

  Copyright blah blah

*/

class NamHitCounter 
{

    /**
     * Initializes the plugin by setting localization, filters, and administration functions.
     */
    public function __construct() 
    {

        // Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        
        // MOVE uninstall feature to uninstall.php
        //register_uninstall_hook( __FILE__, array( $this, 'uninstall' ) );

        // Register hook executes just before WordPress determines which template page to load
        add_action( 'template_redirect', array( $this, 'increase_counter_when_home_visited' ) );
        
        // Add extra submenu to the admin panel
        add_action( 'admin_menu', array( $this, 'create_menu_admin_panel' ) );
        
        // Handle POST request, admin_action_($action)
        add_action( 'admin_action_nam_counter_action', array( $this, 'nam_counter_admin_action' ) );    
        
    } // end constructor

    /**
     * Fired when the plugin is activated.
     * Init nam_hit_counter option in DB
     */
    public function activate( $network_wide ) 
    {   
    
        // if the WordPress version is older than 2.6, deactivate this plugin
        // admin_action_ hook appearance 2.6 
        if ( version_compare( get_bloginfo( 'version' ), '2.6', '<' ) ) {
            deactivate_plugins( basename( __FILE__ ) );
        } else {
            $data = array(
                'counter'   => 0,
                'time'      => null,
                'active'    => true
            );
            add_option( 'nam_hit_counter', $data, '', 'no' );
        }
    } // end activate

    /**
     * Increase counter when home page visited
     */ 
    public function increase_counter_when_home_visited() 
    {
        if (is_home()) {
            $data = get_option( 'nam_hit_counter' );
            if ( $data['active'] ) {
                $data['counter']++;    
                $data['time'] = current_time('mysql');
                update_option( 'nam_hit_counter', $data );
            }
        }
    }

    /**
     * Add submenu into Admin's panel : Settings > Nam HitCounter
     */ 
    public function create_menu_admin_panel() 
    {
        add_options_page( 'Nam HitCounter Options', 'Nam HitCounter', 
        'manage_options', 'nam-hitcounter-unique_identifier', array($this, 'nam_hitcounter_plugin_form' ) );
    }   
    
    /**
     * Create Plugin option page
     */ 
    public function nam_hitcounter_plugin_form() 
    {
        if (!current_user_can( 'manage_options' )) {
            wp_die( __('You do not have sufficient permission to access this page.') );
        }
        
        // Add css only plugin option page
        wp_enqueue_style( 'nam-hitcounter', plugins_url( 'css/admin.css', __FILE__ ) );
                    
        // retrieve counter
        $data = get_option( 'nam_hit_counter' );
        
        // admin form manage counter
        include 'views/admin.php';
    }

    /**
     * Handle reset counter
     * Redirect to a normal page after a POST request to prevent duplicate when user refreshes the page
     */     
    public function nam_counter_admin_action() 
    {
        //verify post is not a revision
        
        if ( isset( $_POST['reset'] ) ) {
            $data = get_option( 'nam_hit_counter' );
            $data['counter'] = 0;
            $data['time'] = null;
            update_option( 'nam_hit_counter', $data );
        }
        
        if ( isset( $_POST['enable'] ) ) {
            $data = get_option( 'nam_hit_counter' );
            $data['active'] = true;
            update_option( 'nam_hit_counter', $data );
        }
        
        if ( isset( $_POST['disable'] ) ) {
            $data = get_option( 'nam_hit_counter' );
            $data['active'] = false;
            update_option( 'nam_hit_counter', $data );
        }
        
        wp_safe_redirect( add_query_arg( 'updated', 'true', wp_get_referer() ) );
        exit();
    }       
    
} // end class

$plugin_name = new NamHitCounter();

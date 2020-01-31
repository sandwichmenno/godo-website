<?php
class Go_bullhorn_settings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Go Bullhorn',
            'Go Bullhorn',
            'manage_options',
            'go_bullhorn_settings',
            array( $this, 'create_admin_page' )
        );
    }
}

if( is_admin() )
    $go_bullhorn_settings = new Go_bullhorn_settings();
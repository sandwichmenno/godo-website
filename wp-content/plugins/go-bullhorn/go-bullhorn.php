<?php
/*
  Plugin name: GoBullhorn
  Description: Bullhorn API for the GoDo website
  Author: Menno van Voorst
  Version: 1.0
  */

namespace go_bullhorn;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require 'includes/class-settings.php';
include( plugin_dir_path( __FILE__ ) . 'includes/class-shortcodes.php');

final class go_bullhorn
{
    public $authorizationCode;

    function __construct()
    {
        do_action('api_connect');
    }

    public static function connect_to_api()
    {
        $client_id = 'eb70e8b5-3260-43b3-bdc2-148e6904aa1';
        $client_secret = '';

        $connection = new Bullhorn_Connection();

        $authorizationCode = $connection->getAuthorizationCode('authorization_code', '', $client_id, $client_secret);
    }

    public static function checkConnection() {

    }
}
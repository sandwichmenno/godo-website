<?php

namespace go_bullhorn;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Bullhorn_Connection
{
    public function __construct()
    {
        add_action( 'api_connect', array( $this, 'getAuthorizationCode' ) );
    }

    public function getAuthorizationCode($grant_type, $callback_url, $client_id, $client_secret) {
        $auth_url = 'https://auth.bullhornstaffing.com/oauth/authorize';

        $response   = wp_remote_post( $auth_url, array(
            'method'      => 'POST',
            'headers'     => array(),
            'body'        => array(
                'grant_type'    => $grant_type,
                'client_id'     => $client_id,
                'client_secret' => $client_secret,
                'redirect_uri'  => $callback_url
            )
        ) );

        $response =  $response['body'] ;
        $content = json_decode($response,true);

        return $content["access_token"];
    }
}
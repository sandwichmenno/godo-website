<?php
function getAuthorizationCode() {
    $options = get_option( 'bullhorn_settings' );

    $url = 'https://auth.bullhornstaffing.com/oauth/authorize';

    $user = $options['username'];
    $pass =  $options['password'];
    $client = $options['client_id'];
    $secret = $options['client_secret'];
    $redirect_uri = get_bloginfo('url') . '/bullhorn-api/';

    $response   = wp_remote_request( $url, array(
        'method'      => 'POST',
        'headers'     => array(),
        'body'        => array(
            'client_id'     => $client,
            'response_type' => 'code',
            'action' => 'Login',
            'username'  => $user,
            'password'  => $pass
        )
    ) );

    $content = json_decode($response,true);
}
add_action( 'admin_post_bullhorn_authenticate', 'getAuthorizationCode' );

/*function authorize() {
    $options = get_option( 'bullhorn_settings' );

    $url = 'https://auth.bullhornstaffing.com/oauth/authorize';

    $user = $options['username'];
    $pass =  $options['password'];
    $client = $options['client_id'];
    $secret = $options['client_secret'];
    $redirect_uri = get_bloginfo('url') . '/bullhorn-api/';

    if ( $client && $secret ) {
        $params = array(
            'client_id' => $client,
            'response_type' => 'code',
        );

        if ( $user && $pass ) {
            $params['username'] = $user;
            $params['password'] = $pass;
            $params['action']   = 'Login';
        }

        $redirect = $url . '?' . build_query( $params );
    }

    echo $redirect;
}
add_action( 'admin_post_bullhorn_authenticate', 'authorize' );*/

function request_access_token( $code ) {
    $options = get_option( 'bullhorn_settings' );

    // API Action URL
    $url = 'https://auth.bullhornstaffing.com/oauth/token';

    $client = $options['client_id'];
    $secret = $options['client_secret'];
    $redirect_uri = get_bloginfo('url') . '/bullhorn-api/';

    $params = array(
        'grant_type'    => 'authorization_code',
        'code'          => $code,
        'client_id'     => $client,
        'client_secret' => $secret,
    );

    $args = array(
        'timeout' => 15,
    );

    $response = wp_remote_post( $url, array(
            'method'      => 'POST',
            'timeout'     => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking'    => true,
            'headers'     => array(),
            'body'        => array(
                'grant_type' => 'authorization_code',
                'code' => $code,
                'client_id' => $client,
                'client_secret' => $secret,
            ),
            'cookies'     => array()
        )
    );

    if ( $response && ! is_wp_error( $response ) ) {

        $body = json_decode( $response['body'] );

        return json_encode($response);
    }

}

function showJobList() {
    $auth_code = getAuthorizationCode('eb70e8b5-3260-43b3-bdc2-148e6904aa19', 'godo.api', 'Liekeisdebest123!', '');

    return request_access_token('23:3Ac1c70246-f440-4540-b13a-f3c6316e5ec5');
}
add_shortcode('bullhorn_jobs', 'showJobList');
<?php

function bullhorn_internal_rewrites(){
    add_rewrite_rule( 'bullhorn-api$', 'index.php?bullhorn-api=1', 'top' );
}
add_action( 'wp_loaded', 'bullhorn_internal_rewrites' );

function bullhorn_internal_query_vars( $query_vars ){
    $query_vars[] = 'bullhorn-api';
    return $query_vars;
}
add_filter( 'query_vars', 'bullhorn_internal_query_vars' );

function bullhorn_internal_rewrites_parse_request( &$wp ){

    if (!array_key_exists( 'bullhorn-api', $wp->query_vars ) ) {
        return;
    }

    // security and validation

    // do whatever you want to do

    die();
}
add_action( 'parse_request', 'bullhorn_internal_rewrites_parse_request' );
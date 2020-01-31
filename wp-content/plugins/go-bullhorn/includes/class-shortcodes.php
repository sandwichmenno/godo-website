<?php

namespace go_bullhorn;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Shortcodes {
    public function __construct() {
        add_shortcode( 'bullhorn_jobs', array( __CLASS__, 'bullhorn_jobs_shortcode' ) );
    }

    public static function bullhorn_jobs_shortcode() {

        return 'bullhorn job listing here';
    }
}
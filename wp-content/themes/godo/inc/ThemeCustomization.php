<?php
function godo_customize_register( $wp_customize ) {
    $wp_customize->add_panel( 'content', array(
        'priority'       => 300,
        'theme_supports' => '',
        'title'          => __( 'Content', 'godo' ),
        'description'    => __( 'Pas de content van de website aan', 'godo' ),
    ) );

    /*Frontpage*/
    $wp_customize->add_section( 'frontpage_section' , array(
        'title'    => __('Frontpage','godo'),
        'panel'    => 'content',
        'priority' => 10
    ) );

    /*Top*/
    /*Banner*/
    $wp_customize->add_setting( 'front_top_banner', array(
        'default'           => __( 'Frontpage banner tekst', 'godo' ),
        'sanitize_callback' => 'sanitize_text_field'
    ) );

    $wp_customize->add_control( new WP_Customize_Control(
            $wp_customize,
            'front_top_banner',
            array(
                'label'    => __( 'Banner tekst', 'godo' ),
                'section'  => 'frontpage_section',
                'settings' => 'front_top_banner',
                'type'     => 'text'
            )
        )
    );

    /*Tagline*/
    $wp_customize->add_setting( 'front_top_tagline', array(
        'default'           => __( 'Frontpage tagline', 'godo' ),
        'sanitize_callback' => 'sanitize_textarea_field'
    ) );

    $wp_customize->add_control( new WP_Customize_Control(
            $wp_customize,
            'front_top_tagline',
            array(
                'label'    => __( 'Tagline', 'godo' ),
                'section'  => 'frontpage_section',
                'settings' => 'front_top_tagline',
                'type'     => 'textarea'
            )
        )
    );

    /*Jobs*/
    /*Header*/
    $wp_customize->add_setting( 'front_job_header', array(
        'default'           => __( 'Frontpage vacature header', 'godo' ),
        'sanitize_callback' => 'sanitize_text_field'
    ) );

    $wp_customize->add_control( new WP_Customize_Control(
            $wp_customize,
            'front_job_header',
            array(
                'label'    => __( 'Vacature kop', 'godo' ),
                'section'  => 'frontpage_section',
                'settings' => 'front_job_header',
                'type'     => 'text'
            )
        )
    );

    /*Body*/
    $wp_customize->add_setting( 'front_job_body', array(
        'default'           => __( 'Frontpage vacature broodtekst', 'godo' ),
        'sanitize_callback' => 'sanitize_textarea_field'
    ) );

    $wp_customize->add_control( new WP_Customize_Control(
            $wp_customize,
            'front_job_body',
            array(
                'label'    => __( 'Vacature broodtekst', 'godo' ),
                'section'  => 'frontpage_section',
                'settings' => 'front_job_body',
                'type'     => 'textarea'
            )
        )
    );

    /*Clients*/
    /*Header*/
    $wp_customize->add_setting( 'front_clients_header', array(
        'default'           => __( 'Frontpage opdrachtgevers header', 'godo' ),
        'sanitize_callback' => 'sanitize_text_field'
    ) );

    $wp_customize->add_control( new WP_Customize_Control(
            $wp_customize,
            'front_clients_header',
            array(
                'label'    => __( 'Opdrachtgevers kop', 'godo' ),
                'section'  => 'frontpage_section',
                'settings' => 'front_clients_header',
                'type'     => 'text'
            )
        )
    );

    /*Body*/
    $wp_customize->add_setting( 'front_clients_body', array(
        'default'           => __( 'Frontpage opdrachtgevers broodtekst', 'godo' ),
        'sanitize_callback' => 'sanitize_textarea_field'
    ) );

    $wp_customize->add_control( new WP_Customize_Control(
            $wp_customize,
            'front_clients_body',
            array(
                'label'    => __( 'Opdrachtgevers broodtekst', 'godo' ),
                'section'  => 'frontpage_section',
                'settings' => 'front_clients_body',
                'type'     => 'textarea'
            )
        )
    );

    /*Button*/
    $wp_customize->add_setting( 'front_clients_button', array(
        'default'           => __( 'Frontpage opdrachtgevers knop', 'godo' ),
        'sanitize_callback' => 'sanitize_text_field'
    ) );

    $wp_customize->add_control( new WP_Customize_Control(
            $wp_customize,
            'front_clients_button',
            array(
                'label'    => __( 'Opdrachtgevers knop', 'godo' ),
                'section'  => 'frontpage_section',
                'settings' => 'front_clients_button',
                'type'     => 'text'
            )
        )
    );
}
add_action( 'customize_register', 'godo_customize_register' );
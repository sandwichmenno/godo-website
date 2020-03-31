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

        /*CTA 1 tekst*/
        $wp_customize->add_setting( 'front_top_cta1_text', array(
            'default'           => __( 'Frontpage cta 1 tekst', 'godo' ),
            'sanitize_callback' => 'sanitize_text_field'
        ) );

        $wp_customize->add_control( new WP_Customize_Control(
                $wp_customize,
                'front_top_cta1_text',
                array(
                    'label'    => __( 'CTA 1 tekst', 'godo' ),
                    'section'  => 'frontpage_section',
                    'settings' => 'front_top_cta1_text',
                    'type'     => 'text'
                )
            )
        );

        /*CTA 1 url*/
        $wp_customize->add_setting( 'front_top_cta1_url', array(
            'default'           => __( 'Frontpage cta 1 url', 'godo' ),
            'sanitize_callback' => 'sanitize_text_field'
        ) );

        $wp_customize->add_control( new WP_Customize_Control(
                $wp_customize,
                'front_top_cta1_url',
                array(
                    'label'    => __( 'CTA 1 tekst', 'godo' ),
                    'section'  => 'frontpage_section',
                    'settings' => 'front_top_cta1_url',
                    'type'     => 'text'
                )
            )
        );

        /*CTA 2 tekst*/
        $wp_customize->add_setting( 'front_top_cta2_text', array(
            'default'           => __( 'Frontpage cta 2 tekst', 'godo' ),
            'sanitize_callback' => 'sanitize_text_field'
        ) );

        $wp_customize->add_control( new WP_Customize_Control(
                $wp_customize,
                'front_top_cta2_text',
                array(
                    'label'    => __( 'CTA 2 url', 'godo' ),
                    'section'  => 'frontpage_section',
                    'settings' => 'front_top_cta2_text',
                    'type'     => 'text'
                )
            )
        );

        /*CTA 2 url*/
        $wp_customize->add_setting( 'front_top_cta2_url', array(
            'default'           => __( 'Frontpage cta 2 url', 'godo' ),
            'sanitize_callback' => 'sanitize_text_field'
        ) );

        $wp_customize->add_control( new WP_Customize_Control(
                $wp_customize,
                'front_top_cta2_url',
                array(
                    'label'    => __( 'CTA 2 tekst', 'godo' ),
                    'section'  => 'frontpage_section',
                    'settings' => 'front_top_cta2_url',
                    'type'     => 'text'
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

        /*Button*/
        $wp_customize->add_setting( 'front_job_button', array(
            'default'           => __( 'Frontpage vacature knop', 'godo' ),
            'sanitize_callback' => 'sanitize_text_field'
        ) );

        $wp_customize->add_control( new WP_Customize_Control(
                $wp_customize,
                'front_job_button',
                array(
                    'label'    => __( 'Vacature knop', 'godo' ),
                    'section'  => 'frontpage_section',
                    'settings' => 'front_job_button',
                    'type'     => 'text'
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

    /*Over GoDo*/
    $wp_customize->add_section( 'about_section' , array(
        'title'    => __('Over GoDo','godo'),
        'panel'    => 'content',
        'priority' => 10
    ) );

        /*Top*/
        /*Banner*/
        $wp_customize->add_setting( 'about_top_banner', array(
            'default'           => __( 'Over GoDo banner tekst', 'godo' ),
            'sanitize_callback' => 'sanitize_text_field'
        ) );

        $wp_customize->add_control( new WP_Customize_Control(
                $wp_customize,
                'about_top_banner',
                array(
                    'label'    => __( 'Banner tekst', 'godo' ),
                    'section'  => 'about_section',
                    'settings' => 'about_top_banner',
                    'type'     => 'text'
                )
            )
        );

        /*Intro*/
        /*Text*/
        $wp_customize->add_setting( 'about_intro_text', array(
            'default'           => __( 'Over GoDo intro tekst', 'godo' ),
            'sanitize_callback' => 'sanitize_textarea_field'
        ) );

        $wp_customize->add_control( new WP_Customize_Control(
                $wp_customize,
                'about_intro_text',
                array(
                    'label'    => __( 'Intro tekst', 'godo' ),
                    'section'  => 'about_section',
                    'settings' => 'about_intro_text',
                    'type'     => 'textarea'
                )
            )
        );

        /*Button*/
        $wp_customize->add_setting( 'about_intro_button', array(
            'default'           => __( 'Over GoDo intro button', 'godo' ),
            'sanitize_callback' => 'sanitize_text_field'
        ) );

        $wp_customize->add_control( new WP_Customize_Control(
                $wp_customize,
                'about_intro_button',
                array(
                    'label'    => __( 'Intro button', 'godo' ),
                    'section'  => 'about_section',
                    'settings' => 'about_intro_button',
                    'type'     => 'text'
                )
            )
        );

        /*Makerstreet*/
        /*Text*/
        $wp_customize->add_setting( 'about_makerstreet_text', array(
            'default'           => __( 'Over GoDo makerstreet tekst', 'godo' ),
            'sanitize_callback' => 'sanitize_textarea_field'
        ) );

        $wp_customize->add_control( new WP_Customize_Control(
                $wp_customize,
                'about_makerstreet_text',
                array(
                    'label'    => __( 'Makerstreet tekst', 'godo' ),
                    'section'  => 'about_section',
                    'settings' => 'about_makerstreet_text',
                    'type'     => 'textarea'
                )
            )
        );

        /*Button*/
        $wp_customize->add_setting( 'about_makerstreet_button', array(
            'default'           => __( 'Over GoDo makerstreet button', 'godo' ),
            'sanitize_callback' => 'sanitize_text_field'
        ) );

        $wp_customize->add_control( new WP_Customize_Control(
                $wp_customize,
                'about_makerstreet_button',
                array(
                    'label'    => __( 'Makerstreet button', 'godo' ),
                    'section'  => 'about_section',
                    'settings' => 'about_makerstreet_button',
                    'type'     => 'text'
                )
            )
        );

        /*Klanten*/
        /*Kop*/
        $wp_customize->add_setting( 'about_clients_header', array(
            'default'           => __( 'Over GoDo klanten kop', 'godo' ),
            'sanitize_callback' => 'sanitize_text_field'
        ) );

        $wp_customize->add_control( new WP_Customize_Control(
                $wp_customize,
                'about_clients_header',
                array(
                    'label'    => __( 'Klanten kop', 'godo' ),
                    'section'  => 'about_section',
                    'settings' => 'about_clients_header',
                    'type'     => 'text'
                )
            )
        );

        /*Button*/
        $wp_customize->add_setting( 'about_clients_button', array(
            'default'           => __( 'Over GoDo klanten button', 'godo' ),
            'sanitize_callback' => 'sanitize_text_field'
        ) );

        $wp_customize->add_control( new WP_Customize_Control(
                $wp_customize,
                'about_clients_button',
                array(
                    'label'    => __( 'Klanten button', 'godo' ),
                    'section'  => 'about_section',
                    'settings' => 'about_clients_button',
                    'type'     => 'text'
                )
            )
        );

    /*Over GoDo*/
    $wp_customize->add_section( 'contact_section' , array(
        'title'    => __('Contact','godo'),
        'panel'    => 'content',
        'priority' => 10
    ) );

        /*Top*/
        /*Banner*/
        $wp_customize->add_setting( 'contact_top_banner', array(
            'default'           => __( 'Contact banner tekst', 'godo' ),
            'sanitize_callback' => 'sanitize_text_field'
        ) );

        $wp_customize->add_control( new WP_Customize_Control(
                $wp_customize,
                'contact_top_banner',
                array(
                    'label'    => __( 'Banner tekst', 'godo' ),
                    'section'  => 'contact_section',
                    'settings' => 'contact_top_banner',
                    'type'     => 'text'
                )
            )
        );

        /*Intro*/
        /*Text*/
        $wp_customize->add_setting( 'contact_intro_text', array(
            'default'           => __( 'Contact intro tekst', 'godo' ),
            'sanitize_callback' => 'sanitize_textarea_field'
        ) );

        $wp_customize->add_control( new WP_Customize_Control(
                $wp_customize,
                'contact_intro_text',
                array(
                    'label'    => __( 'Intro tekst', 'godo' ),
                    'section'  => 'contact_section',
                    'settings' => 'contact_intro_text',
                    'type'     => 'textarea'
                )
            )
        );

        /*Button*/
        $wp_customize->add_setting( 'contact_intro_button', array(
            'default'           => __( 'Contact intro button', 'godo' ),
            'sanitize_callback' => 'sanitize_text_field'
        ) );

        $wp_customize->add_control( new WP_Customize_Control(
                $wp_customize,
                'contact_intro_button',
                array(
                    'label'    => __( 'Intro button', 'godo' ),
                    'section'  => 'contact_section',
                    'settings' => 'contact_intro_button',
                    'type'     => 'text'
                )
            )
        );
}
add_action( 'customize_register', 'godo_customize_register' );
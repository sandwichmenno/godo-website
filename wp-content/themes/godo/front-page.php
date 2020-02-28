<?php get_header(); ?>

<section class="page-section hero-wrapper" style="background: #ffffff; padding-bottom: 64px;">
    <div class="hero" style="background: #f4f4f4 url('<?php bloginfo('template_directory'); ?>/assets/images/heros/hero_front.jpg') no-repeat center center; background-size: cover;"></div>
    <div class="container">
        <div class="hero-title"><h1><?php echo get_theme_mod( 'front_top_banner'); ?></h1></div>
        <p class="hero-subtitle"><?php echo get_theme_mod( 'front_top_tagline'); ?></p>
        <a href="/over-godo" class="button primary dark">Meer over GoDo</a>
        <a href="/contact" class="button primary dark" style="margin-left: 16px;">Neem contact op</a>
    </div>
</section>

<section class="page-section gray">
    <div class="container row">
        <div class="block intro">
            <h2><?php echo get_theme_mod( 'front_job_header'); ?></h2>
            <p><?php echo get_theme_mod( 'front_job_body'); ?></p>
        </div>
    </div>

    <div class="job-categories slider container">
        <div class="slider-wrapper">
            <div class="slider-item">
                <a href="/vacatures/?category=agile">
                    <div class="block">
                        <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/icon_agile.svg" class="icon"/>
                        <h2>Agile vacatures</h2>
                    </div>
                </a>
            </div>

            <div class="slider-item">
                <a href="/vacatures/?category=design">
                    <div class="block">
                        <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/icon_design.svg" class="icon"/>
                        <h2>Design vacatures</h2>
                    </div>
                </a>
            </div>

            <div class="slider-item">
                <a href="/vacatures/?category=dev">
                    <div class="block">
                        <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/icon_dev.svg" class="icon"/>
                        <h2>Development vacatures</h2>
                    </div>
                </a>
            </div>

            <div class="slider-item">
                <a href="/vacatures/?category=infra">
                    <div class="block">
                        <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/icon_infra.svg" class="icon"/>
                        <h2>Infrastructuur vacatures</h2>
                    </div>
                </a>
            </div>

            <div class="slider-item">
                <a href="/vacatures/?category=misc">
                    <div class="block">
                        <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/icon_misc.svg" class="icon"/>
                        <h2>Overige vacatures</h2>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="container more-jobs">
        <a href="/vacatures" class="button primary dark">Nog meer vacatures</a>
    </div>
</section>

<section class="page-section" id="company">
    <div class="container">
        <div class="block image">
            <img src="<?php bloginfo('template_directory'); ?>/assets/images/temp/helloSmall.jpg"/>
            <div class="block-content">
                <div class="content">
                    <h2><?php echo get_theme_mod( 'front_clients_header'); ?></h2>
                    <p><?php echo get_theme_mod( 'front_clients_body'); ?></p>
                    <a href="/contact" class="button secondary"><?php echo get_theme_mod( 'front_clients_button'); ?></a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="page-section">
    <div class="showcase slider">
        <div class="slider-wrapper">
            <div class="slider-item">
                <div class="block image">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/temp/slider1.jpg"/>
                </div>
            </div>

            <div class="slider-item">
                <div class="block image">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/temp/slider2.jpg"/>
                </div>
            </div>

            <div class="slider-item">
                <div class="block image">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/temp/slider3.jpg"/>
                </div>
            </div>

            <div class="slider-item">
                <div class="block image">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/temp/slider4.jpg"/>
                </div>
            </div>

            <div class="slider-item">
                <div class="block image">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/temp/slider5.jpg"/>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    jQuery('.job-categories .slider-wrapper').slick({
        dots: false,
        infinite: true,
        arrows: false,
        speed: 300,
        slidesToShow: 5,
        slidesToScroll: 5,
        adaptiveWidth: true,
        responsive: [
            {
                breakpoint: 1025,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 4,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });

    jQuery('.showcase .slider-wrapper').slick({
        dots: true,
        infinite: true,
        arrows: false,
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 3,
        adaptiveWidth: true,
        responsive: [
            {
                breakpoint: 1025,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: true
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });
</script>

<?php get_footer(); ?>
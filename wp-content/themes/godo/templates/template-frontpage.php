<?php
/**
 * Template Name: Frontpage Template
 */
?>

<?php get_header(); ?>

<section class="page-section hero-wrapper" style="background: #ffffff; padding-bottom: 64px;">
    <div class="hero" style="background: #f4f4f4 url('<?php echo get_the_post_thumbnail_url(); ?>') no-repeat center center; background-size: cover;"></div>
    <div class="container">
        <div class="hero-title"><h1><?php echo $lang['frontpage']['banner']['title']; ?></h1></div>
        <p class="hero-subtitle"><?php echo $lang['frontpage']['banner']['subtitle']; ?></p>
        <a href="<?php echo $lang['frontpage']['banner']['cta_1_url']; ?>" class="button primary dark"><?php echo $lang['frontpage']['banner']['cta_1']; ?></a>
        <a href="<?php echo $lang['frontpage']['banner']['cta_2_url']; ?>" class="button primary dark"><?php echo $lang['frontpage']['banner']['cta_2']; ?></a>
    </div>
</section>

<section class="page-section gray">
    <div class="container row">
        <div class="block intro">
            <h2><?php echo $lang['frontpage']['vacatures']['title']; ?></h2>
            <p><?php echo $lang['frontpage']['vacatures']['body']; ?></p>
        </div>
    </div>

    <div class="job-categories slider container">
        <div class="slider-wrapper">
            <div class="slider-item">
                <a href="/vacatures/agile">
                    <div class="block">
                        <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/icon_agile.svg" class="icon"/>
                        <h2>Agile vacatures</h2>
                    </div>
                </a>
            </div>

            <div class="slider-item">
                <a href="/vacatures/design">
                    <div class="block">
                        <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/icon_design.svg" class="icon"/>
                        <h2>Design vacatures</h2>
                    </div>
                </a>
            </div>

            <div class="slider-item">
                <a href="/vacatures/dev">
                    <div class="block">
                        <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/icon_dev.svg" class="icon"/>
                        <h2>Development vacatures</h2>
                    </div>
                </a>
            </div>

            <div class="slider-item">
                <a href="/vacatures/infra">
                    <div class="block">
                        <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/icon_infra.svg" class="icon"/>
                        <h2>Infrastructuur vacatures</h2>
                    </div>
                </a>
            </div>

            <div class="slider-item">
                <a href="/vacatures/misc">
                    <div class="block">
                        <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/icon_misc.svg" class="icon"/>
                        <h2>Overige vacatures</h2>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="container more-jobs">
        <a href="<?php echo $lang['frontpage']['vacatures']['cta_url']; ?>" class="button primary dark"><?php echo $lang['frontpage']['vacatures']['cta']; ?></a>
    </div>
</section>

<section class="page-section" id="company">
    <div class="container">
        <div class="block image">
            <?php $companyImage = get_post_meta(get_queried_object_id(), 'company',true); ?>
            <img src="<?php echo wp_get_attachment_image_src($companyImage, "full")[0]; ?>"/>
            <div class="block-content">
                <div class="content">
                    <h2><?php echo $lang['frontpage']['bedrijven']['title']; ?></h2>
                    <p><?php echo $lang['frontpage']['bedrijven']['body']; ?></p>
                    <a href="<?php echo $lang['frontpage']['bedrijven']['cta_url']; ?>" class="button secondary"><?php echo $lang['frontpage']['bedrijven']['cta']; ?></a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="page-section">
    <div class="showcase slider">
        <div class="slider-wrapper">
            <?php

            $args = array(
                'post_type' => 'slider',
                'order' => 'asc'
            );
            $your_loop = new WP_Query( $args );

            if ( $your_loop->have_posts() ) : while ( $your_loop->have_posts() ) : $your_loop->the_post(); ?>

                <div class="slider-item">
                    <div class="block image">
                        <img src="<?php the_post_thumbnail_url(); ?>"/>
                    </div>
                </div>

            <?php endwhile; endif; wp_reset_postdata(); ?>
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
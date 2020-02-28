<?php
/**
 * Template Name: Default Template
 */
?>

<?php get_header(); ?>

    <section class="page-section hero-wrapper">
        <div class="hero" style="background: #f4f4f4 url('<?php bloginfo('template_directory'); ?>/assets/images/heros/hero_about.jpg') no-repeat center center; background-size: cover;"></div>
        <div class="container row">
            <div class="hero-title"><h1><?php the_title(); ?></h1></div>
        </div>
    </section>

    <section class="page-section ">
        <div class="container row">
            <?php
            // TO SHOW THE PAGE CONTENTS
            while ( have_posts() ) : the_post(); ?>
                    <?php the_content(); ?>
                <?php
            endwhile; //resetting the page loop
            wp_reset_query(); //resetting the page query
            ?>
        </div>
    </section>

<?php get_footer(); ?>
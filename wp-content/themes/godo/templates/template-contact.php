<?php
/**
 * Template Name: Contact Template
 */
?>

<?php get_header(); ?>
    <?php get_template_part( 'templates/parts/popup', 'contact' ); ?>

    <section class="page-section hero-wrapper">
        <div class="hero" style="background: #f4f4f4 url('<?php bloginfo('template_directory'); ?>/assets/images/heros/hero_contact.jpg') no-repeat center center; background-size: cover;"></div>
        <div class="container row">
            <div class="hero-title"><h1>Kopje koffie?</h1></div>
        </div>
    </section>

    <section class="page-section gray" id="about">
        <div class="container row">
            <div class="block intro row">
                <p>Wij zijn altijd bereikbaar. Nou ja, wij houden ons enigszins aan kantooruren. Maar je mag het altijd proberen. Moet je niet schrikken als wij gewoon in de sportschool opnemen. Of terugbellen als je in de kroeg staat.</p>
                <a class="button secondary dark open-popup">Stuur ons een berichtje</a>
            </div>
        </div>
    </section>

    <section class="page-section team">
        <div class="container row">
            <?php

            $args = array(
                'post_type' => 'members',
                'order' => 'asc'
            );
            $your_loop = new WP_Query( $args );

            if ( $your_loop->have_posts() ) : while ( $your_loop->have_posts() ) : $your_loop->the_post();
                $email = get_post_meta( $post->ID, '__email', true );
                $phone = get_post_meta( $post->ID, '__phone', true ); ?>

                <div class="block image">
                    <div class="block-header">
                        <img src="<?php the_post_thumbnail_url(); ?>"/>
                        <div class="contact-buttons row">
                            <a href="/" class="button secondary"><?php echo $email ?></a>
                            <a href="/" class="button secondary"><?php echo $phone ?></a>
                        </div>
                    </div>
                </div>

            <?php endwhile; endif; wp_reset_postdata(); ?>
            </div>
    </section>

    <section class="page-section map">
        <div class="block">
            <div class="block-header">
                <strong>Generaal Vetterstraat 82</strong>
                <br/>1059 BW Amsterdam
            </div>

            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2437.621149291742!2d4.860533215530286!3d52.34101865723077!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c5e206e19ec301%3A0x39d6b421b1d2b785!2sMakerStreet!5e0!3m2!1snl!2snl!4v1582300261678!5m2!1snl!2snl" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
        </div>
    </section>

<?php get_footer(); ?>
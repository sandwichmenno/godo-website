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
                $phone = get_post_meta( $post->ID, '__phone', true );
                $hideOnContact = get_post_meta( $post->ID, '__visibility', true ); ?>

                <?php if($hideOnContact !== "true") : ?>
                    <div class="block image">
                        <div class="block-header">
                            <img src="<?php the_post_thumbnail_url(); ?>"/>
                            <div class="contact-buttons row">
                                <h3><?php the_title() ?></h3>
                                <?php if($email) { ?><a href="mailto:<?php echo $email; ?>" class="button secondary dark"><?php echo $email ?></a><?php } ?>
                                <?php if($phone) { ?><a href="tel:<?php echo $phone; ?>" class="button secondary dark"><?php echo $phone ?></a><?php } ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            <?php endwhile; endif; wp_reset_postdata(); ?>
            </div>
    </section>

    <section class="page-section map">
        <div class="block">
            <div class="block-header">
                <strong>Fred. Roeskestraat 99</strong>
                <br/>1076 EE Amsterdam
            </div>

            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d9750.476230375674!2d4.8624824!3d52.3410566!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xcea46d14707c53cd!2sGoDo!5e0!3m2!1snl!2snl!4v1582795439527!5m2!1snl!2snl" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
        </div>
    </section>

<?php get_footer(); ?>
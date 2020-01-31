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
            <div class="hero-title"><h1>Wanneer doen we een kop koffie?</h1></div>
        </div>
    </section>

    <section class="page-section gray">
        <div class="container">
            <div class="block intro row">
                <p>Wij zijn altijd bereikbaar. Nou ja, wij houden ons enigszins aan kantooruren. Maar je mag het altijd proberen. Moet je niet schrikken als wij gewoon in de sportschool opnemen. Of terugbellen als je in de kroeg staat.</p>
                <a class="button secondary dark open-popup">Stuur een bericht</a>
            </div>
        </div>
    </section>

    <section class="page-section team">
        <div class="container">
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

            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2437.5123368152645!2d4.8438252154385495!3d52.34299245708492!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c5e1f6c404975f%3A0xeb7e9fbdc19f9620!2sMakerstreet%20Interim!5e0!3m2!1snl!2snl!4v1579254859323!5m2!1snl!2snl" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
        </div>
    </section>

<?php get_footer(); ?>
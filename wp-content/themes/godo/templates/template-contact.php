<?php
/**
 * Template Name: Contact Template
 */
?>

<?php get_header(); ?>
    <?php get_template_part( 'templates/parts/popup', 'contact' ); ?>

    <section class="page-section hero-wrapper">
        <div class="hero" style="background: #f4f4f4 url('<?php echo get_the_post_thumbnail_url(); ?>') no-repeat center center; background-size: cover;"></div>
        <div class="container row">
            <div class="hero-title"><h1><?php echo $lang['contact']['banner']['title']; ?></h1></div>
        </div>
    </section>

    <section class="page-section gray" id="about">
        <div class="container row">
            <div class="block intro row">
                <p><?php echo $lang['contact']['banner']['body']; ?></p>
                <a class="button secondary dark open-popup"><?php echo $lang['contact']['banner']['cta']; ?></a>
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
                            <div class="image"><img src="<?php the_post_thumbnail_url(); ?>"/></div>
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
                <strong><?php echo $lang['contact']['address']['street']; ?></strong>
                <br/><?php echo $lang['contact']['address']['city']; ?>
            </div>

            <iframe src="<?php echo $lang['contact']['address']['maps']; ?>" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
        </div>
    </section>

<?php get_footer(); ?>
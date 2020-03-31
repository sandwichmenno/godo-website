<?php
/**
 * Template Name: About Template
 */
?>

<?php get_header(); ?>

    <section class="page-section hero-wrapper">
        <div class="hero" style="background: #f4f4f4 url('<?php echo get_the_post_thumbnail_url(); ?>') no-repeat center center; background-size: cover;"></div>
        <div class="container row">
            <div class="hero-title"><h1><?php echo $lang['about']['banner']['title']; ?></h1></div>
        </div>
    </section>

    <section class="page-section gray" id="about">
        <div class="container row">
            <div class="block intro row">
                <div>
                    <p><?php echo $lang['about']['banner']['body']; ?></p>
                    <a href="<?php echo $lang['about']['banner']['cta_url']; ?>" class="button secondary dark"><?php echo $lang['about']['banner']['cta']; ?></a>
                </div>
            </div>

            <div class="block image" style="margin-top: 32px;">
                <?php $teamImage = get_post_meta(get_queried_object_id(), 'team',true); ?>
                <img src="<?php echo wp_get_attachment_image_src($teamImage, 'full')[0]; ?>"/>
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
                $linkedin = get_post_meta( $post->ID, '__linkedin', true );
                $email = get_post_meta( $post->ID, '__email', true );
                $phone = get_post_meta( $post->ID, '__phone', true ); ?>

                <div class="block image">
                    <div class="block-header">
                        <div class="image"><img src="<?php the_post_thumbnail_url(); ?>"/></div>
                        <h2><?php the_title() ?> <a href="<?php echo $linkedin ?>" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/linkedin-white.svg" class="icon"/></a></h2>
                    </div>

                    <div class="block-content row">
                        <p><?php the_content() ?></p>
                        <?php if($email) { ?><a href="mailto:<?php echo $email; ?>" class="button secondary">Mail: <?php echo $email ?>></a><?php } ?>
                        <?php if($phone) { ?><a href="tel:<?php echo $phone; ?>" class="button secondary">Bel:  <?php echo $phone ?></a><?php } ?>
                    </div>
                </div>

            <?php endwhile; endif; wp_reset_postdata(); ?>
        </div>
    </section>

    <section class="page-section gray">
        <div class="container">
            <div class="block intro" style="text-align: center;">
                <?php $makerstreetImage = get_post_meta(get_queried_object_id(), 'makerstreet',true); ?>
                <img src="<?php echo wp_get_attachment_image_src($makerstreetImage, 'full')[0]; ?>" style="margin-bottom: 16px;"/>
                <div class="row">
                    <p><?php echo $lang['about']['makerstreet']['body']; ?></p>
                    <a href="<?php echo $lang['about']['makerstreet']['cta_url']; ?>" target="_blank" class="button secondary dark" style="margin: 0 auto;"><?php echo $lang['about']['makerstreet']['cta']; ?></a>
                </div>
            </div>
        </div>
    </section>

    <section class="page-section">
        <div class="container">
            <div class="block intro image clients full row">
                <h2><?php echo $lang['about']['klanten']['title']; ?></h2>
                <?php

                $args = array(
                    'post_type' => 'clients',
                    'order' => 'asc'
                );
                $your_loop = new WP_Query( $args );

                if ( $your_loop->have_posts() ) : while ( $your_loop->have_posts() ) : $your_loop->the_post(); ?>
                    <div class="client"><img src="<?php the_post_thumbnail_url(); ?>" /></div>
                <?php endwhile; endif; wp_reset_postdata(); ?>
            </div>

            <div style="text-align: center;"><a href="<?php echo $lang['about']['klanten']['cta_url']; ?>" class="button primary dark"><?php echo $lang['about']['klanten']['cta']; ?></a></div>
        </div>
    </section>

<?php get_footer(); ?>
<?php
/**
 * Template Name: About Template
 */
?>

<?php get_header(); ?>

    <section class="page-section hero-wrapper">
        <div class="hero" style="background: #f4f4f4 url('<?php bloginfo('template_directory'); ?>/assets/images/heros/hero_about.jpg') no-repeat center center; background-size: cover;"></div>
        <div class="container row">
            <div class="hero-title"><h1>Recruitment is een ambacht</h1></div>
        </div>
    </section>

    <section class="page-section gray">
        <div class="container">
            <div class="block intro row">
                <p>Wij geloven dat de juiste persoon op de juiste plaats bedrijven sneller laat groeien. Wij gaan voor een lange termijn relatie met onze opdrachtgevers en onze kandidaten. Daarom nemen wij ook de tijd om de juiste match te maken. Zonder duwen of trekken. Daarbij kijken wij niet alleen naar de bewezen skills van onze kandidaten, maar ook naar hun groeipotentieel. Recruitment is een ambacht. GoDo it.</p>
                <p>Wij zijn gespecialiseerd in het bemiddelen van digitale professionals, waaronder developers, designers, agile professionals, data scientisten, growth hackers en digitaal strategen.</p>
                <a href="/" class="button secondary dark">Neem contact op</a>
            </div>
        </div>
    </section>

    <section class="page-section">
        <div class="block image">
            <img src="<?php bloginfo('template_directory'); ?>/assets/images/temp/kerstfeest2019.jpg"/>
        </div>
    </section>

    <section class="page-section team">
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
                    <img src="<?php the_post_thumbnail_url(); ?>"/>
                    <h2><?php the_title() ?> <a href="<?php echo $linkedin ?>"><img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/linkedin-white.svg" class="icon"/></a></h2>
                </div>

                <div class="block-content row">
                    <p><?php the_content() ?></p>
                    <a href="/" class="button secondary">Mail: <?php echo $email ?>></a>
                    <a href="/" class="button secondary">Bel:  <?php echo $phone ?></a>
                </div>
            </div>

        <?php endwhile; endif; wp_reset_postdata(); ?>
    </section>

    <section class="page-section gray">
        <div class="container">
            <div class="block intro image clients full row">
                <h2>Wij werken oa. voor</h2>
                <?php

                $args = array(
                    'post_type' => 'clients',
                    'order' => 'asc'
                );
                $your_loop = new WP_Query( $args );

                if ( $your_loop->have_posts() ) : while ( $your_loop->have_posts() ) : $your_loop->the_post(); ?>
                    <img src="<?php the_post_thumbnail_url(); ?>" />
                <?php endwhile; endif; wp_reset_postdata(); ?>
                <a href="/" class="button primary dark">Heb jij ook een job voor ons?</a>
            </div>
        </div>
    </section>

<?php get_footer(); ?>
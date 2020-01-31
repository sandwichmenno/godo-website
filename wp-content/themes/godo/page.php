<?php
/**
 * Template Name: Jobs Template
 */
?>

<?php get_header(); ?>

    <section class="page-section hero-wrapper">
        <div class="hero no-image" style="background: #f4f4f4">
            <h1>Onze vacatures</h1>
            <form id="filter" class="row">
                <select name="position">
                    <option value="all" selected>Alle functies</option>
                    <option value="back-end developer">Back-end developer</option>
                    <option value="front-end developer">Front-end developer</option>
                    <option value="ux designer">Ux Designer</option>
                    <option value="visual designer">Visual Designer</option>
                </select>

                <select name="location">
                    <option value="all" selected>Alle locaties</option>
                    <option value="amersfoort">Amersfoort</option>
                    <option value="amsterdam">Amsterdam</option>
                    <option value="rotterdam">Rotterdam</option>
                    <option value="amsterdam">Utrecht</option>
                </select>
            </form>
        </div>
    </section>

    <section class="job-section">
        <?php
        // TO SHOW THE PAGE CONTENTS
        while ( have_posts() ) : the_post(); ?> <!--Because the_content() works only inside a WP Loop -->
            <div class="entry-content-page">
                <?php the_content(); ?> <!-- Page Content -->
                <?php echo do_shortcode('[bullhorn_jobs]'); ?>
            </div><!-- .entry-content-page -->

        <?php
        endwhile; //resetting the page loop
        wp_reset_query(); //resetting the page query
        ?>
    </section>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('.page-footer-wrapper').css('bottom', '56px');
        });
    </script>

<?php get_footer(); ?>
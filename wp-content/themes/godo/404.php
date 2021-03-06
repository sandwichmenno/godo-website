<?php get_header(); ?>

    <section class="page-section hero-wrapper" style="background: transparent;">
        <div class="hero" style="background: #f4f4f4 url('<?php echo get_the_post_thumbnail_url(get_page_by_title( 'Vacature niet gevonden' )->ID); ?>') no-repeat center center; background-size: cover;"></div>
        <div class="container row" style="background: transparent;">
            <div class="hero-title"><h1><?php echo $lang['misc']['404']['title']; ?></h1></div>
        </div>
    </section>

    <section class="page-section" id="not-found">
        <div class="container row">
            <div class="col">
                <h2><?php echo $lang['misc']['404']['body']; ?></h2>
                <h3><?php echo $lang['misc']['404']['redirect']; ?></h3>
                <ul>
                    <li><a href="/"><?php echo $lang['misc']['404']['homepageURL']; ?></a></li>
                    <li><a href="/vacatures"><?php echo $lang['misc']['404']['vacatureURL']; ?></a></li>
                    <li><a href="/contact"><?php echo $lang['misc']['404']['homepageURL']; ?></a></li>
                </ul>
            </div>
            <div class="col image"><img src="<?php bloginfo('template_directory'); ?>/assets/images/temp/sad_laptop.png" /></div>
        </div>
    </section>

<?php get_footer(); ?>
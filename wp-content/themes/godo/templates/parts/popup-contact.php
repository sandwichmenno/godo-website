<div id="contact" class="popup-wrapper hidden">
    <div class="popup-header">
        <a href="<?php bloginfo('url') ?>"><img src="<?php bloginfo('template_directory'); ?>/assets/images/logo.svg" class="logo"/></a>
        <div class="close">sluiten <span></span></div>
    </div>

    <section class="page-section hero-wrapper">
        <div class="hero" style="background: #f4f4f4 url('<?php bloginfo('template_directory'); ?>/assets/images/heros/hero_contact2.jpg') no-repeat center center; background-size: cover;"></div>
        <div class="container row">
            <div class="hero-title"><h1>Let’s get in touch</h1></div>
        </div>
    </section>

    <div class="container">
        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('contact-form') ) :

        endif; ?>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#contact .close, .open-popup').click(function (e) {
            if (jQuery('#contact').toggleClass('is-open').hasClass('is-open')) {
                jQuery('body').css('overflow', 'hidden');
                jQuery('#contact').removeClass('hidden');
            } else {
                jQuery('body').css('overflow', 'auto');
                jQuery('#contact').addClass('hidden');
            }
        });
    });
</script>
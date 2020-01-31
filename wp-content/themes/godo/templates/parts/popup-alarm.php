<div id="alarm" class="popup-wrapper hidden">
    <div class="popup-header">
        <a href="<?php bloginfo('url') ?>"><img src="<?php bloginfo('template_directory'); ?>/assets/images/logo.svg" class="logo"/></a>
        <div class="close">sluiten <span></span></div>
    </div>

    <section class="page-section hero-wrapper alarm">
        <div class="hero" style="background: #f4f4f4 url('<?php bloginfo('template_directory'); ?>/assets/images/heros/hero_alarm.jpg') no-repeat center center; background-size: cover;"></div>
        <div class="container row">
            <div class="hero-title"><h1>Mis nooit meer een vacature</h1></div>
        </div>
    </section>

    <div class="container">
        <p>Krijg direct een notificatie in je mailbox wanneer er een nieuwe vacature wordt geplaatst met een UX Design functie in de omgeving van Amsterdam. Handig voor als je geen zin hebt om elke dag de site in de gaten te houden ;)</p>
        <form action="/" method="post" class="wpcf7-form">
            <p><label> Naam<br>
                    <span class="wpcf7-form-control-wrap username"><input type="text" name="username" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false" placeholder="John Doe"></span> </label></p>
            <p><label> Email<br>
                    <span class="wpcf7-form-control-wrap email"><input type="email" name="email" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email" aria-required="true" aria-invalid="false" placeholder="john@doe.nl"></span> </label></p>
            <p><input type="submit" value="Versturen" class="wpcf7-form-control wpcf7-submit"><span class="ajax-loader"></span></p>
            <div class="wpcf7-response-output wpcf7-display-none"></div></form>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#alarm .close, .open-alarm').click(function (e) {
            if (jQuery('#alarm').toggleClass('is-open').hasClass('is-open')) {
                jQuery('body').css('overflow', 'hidden');
                jQuery('#alarm').removeClass('hidden');
            } else {
                jQuery('body').css('overflow', 'auto');
                jQuery('#alarm').addClass('hidden');
            }
        });
    });
</script>
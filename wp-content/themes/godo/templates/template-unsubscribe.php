<?php
/**
 * Template Name: Unsubscribe Template
 */
?>

<?php get_header(); ?>

    <section class="page-section hero-wrapper">
        <div class="hero" style="background: #f4f4f4 url('<?php bloginfo('template_directory'); ?>/assets/images/heros/hero_about.jpg') no-repeat center center; background-size: cover;"></div>
        <div class="container row">
            <div class="hero-title"><h1>Uitschrijven</h1></div>
        </div>
    </section>

    <section class="page-section ">
        <div class="container row">
            <?php echo $lang['misc']['alarm']['unsubscribe']; ?>
            <div id="unsubscribe" class="button primary"><?php echo $lang['misc']['alarm']['unsubscribeButton']; ?></div>
            <div id="loader" style="display: none;"><img src="<?php bloginfo('template_directory'); ?>/assets/images/loader.svg"></div>
            <div id="alert"></div>
        </div>
    </section>

    <script type="text/javascript">
        jQuery('#unsubscribe').click(function (e) {
            const email = "<?php $_GET['email']; ?>";
            const token = "<?php echo $_GET['token']; ?>";

            const data = {
                action: "removeAlarm",
                email: email,
                token: token,
            };

            const failed = "<?php echo $lang['misc']['alarm']['unsubscribeFailed']; ?>";
            const success = "<?php echo $lang['misc']['alarm']['unsubscribeSuccess']; ?>";

            jQuery.ajax({
                type: 'POST',
                url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                dataType: "json"
                data: data,
                beforeSend: function() {
                    jQuery('#loader').show();
                    jQuery('#submit').hide();
                },
                complete: function() {
                    jQuery('#loader').hide();
                    jQuery('#submit').show();
                },
                success: function (response) {
                    if(response.success) {
                        jQuery("#alert").show().html(success);
                        jQuery("#unsubscribe").hide();
                    } else {
                        jQuery("#alert").show().html(failed);
                    }
                },
            });
        });
    </script>

<?php get_footer(); ?>
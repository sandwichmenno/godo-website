<?php
    $theme_settings = get_option( 'theme_settings' );

    $categoryFilter = preg_split('/\r\n|\r|\n/', $theme_settings['filtersJob']);
    $locationFilter = preg_split('/\r\n|\r|\n/', $theme_settings['filtersLocation']);
?>

<div id="alarm" class="popup-wrapper hidden">
    <div class="wrapper">
        <div class="popup-header">
            <a href="<?php bloginfo('url') ?>"><img src="<?php bloginfo('template_directory'); ?>/assets/images/logo.svg" class="logo"/></a>
            <div class="close">sluiten <span></span></div>
        </div>

        <div id="alarmForm">
            <section class="page-section hero-wrapper alarm">
                <div class="hero" style="background: #f4f4f4 url('<?php bloginfo('template_directory'); ?>/assets/images/heros/hero_alarm.jpg') no-repeat center center; background-size: cover;"></div>
                <div class="container row">
                    <div class="hero-title"><h1>Mis nooit meer een vacature</h1></div>
                </div>
            </section>

            <div class="container">
                <p>Krijg direct een notificatie in je mailbox wanneer er een nieuwe vacature wordt geplaatst! Handig voor als je geen zin hebt om elke dag de site in de gaten te houden ;)</p>
                <form action="/" id="setAlarm" method="post">
                    <div class="row filter">
                        <select id="position" name="position">
                            <option value="" disabled selected>Functie</option>
                            <?php foreach($categoryFilter as $filter) { ?>
                                <option value="<?php echo $filter; ?>"><?php echo $filter; ?></option>
                            <?php } ?>
                        </select>

                        <select id="location" name="location">
                            <option value="" disabled selected>Locatie</option>
                            <?php foreach($locationFilter as $filter) { ?>
                                <option value="<?php echo $filter; ?>"><?php echo preg_replace('/\[(.*?)\]/', "" , $filter); ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <p>
                        <label>Naam (verplicht)</label>
                        <input type="text" placeholder="John Doe" id="name" name="name" />
                    </p>

                    <p>
                        <label>Email (verplicht)</label>
                        <input type="text" placeholder="john@doe.nl" id="email" name="email" />
                    </p>

                    <p>
                        <input type="submit" name="alarm" value="Alarm instellen"/>
                    </p>
                </form>
            </div>
        </div>

        <div id="successScreen" style="display: none;">
            <section class="page-section hero-wrapper">
                <div class="hero" style="background: #f4f4f4 url('<?php bloginfo('template_directory'); ?>/assets/images/heros/hero_success.jpg') no-repeat center center; background-size: cover;"></div>
                <div class="container row">
                    <div class="hero-title"><h1>We hebben de alert ingesteld!</h1></div>
                </div>
            </section>

            <div class="container">
                <p>Het is gelukt! Zodra wij een nieuwe vacature online hebben geplaatst die voldoet aan de ingevulde criteria ontvang je een email met een link naar deze vacature.</p>

                <div style="margin-top: 16px; text-align: center;">
                    <h2>Functie</h2>
                    <span><p id="filledJob"></p></span>

                    <h2 style="margin-top: 16px;">Locatie</h2>
                    <span><p id="filledLocation"></p></span>
                </div>

                <p style="float: right;"><div class="button primary dark close" style="margin-left: auto;">Over en sluiten</div></p>
            </div>
        </div>
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

        jQuery('form#setAlarm').on('submit', function(e){
            event.preventDefault();

            const form_data = new FormData();
            const data = {
                name: jQuery("#name").val(),
                email: jQuery("#email").val(),
                location: jQuery("#location").val(),
                job: jQuery("#position").val(),
            };

            form_data.append('action', 'setAlarm');
            form_data.append('name', data['name']);
            form_data.append('email', data['email']);
            form_data.append('location', data['location']);
            form_data.append('job', data['job']);

            jQuery.ajax({
                type: 'POST',
                url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                processData: false,
                contentType: false,
                data: form_data,
                success: function(response){
                    console.log(response);

                    jQuery('#filledJob').text(data['job']);
                    jQuery('#filledLocation').text(data['location'] === null ? "Overal" : data['location']);

                    jQuery('#successScreen').show();
                    jQuery('#alarmForm').remove();
                    scrollToTop();
                },
            });
        });

        function scrollToTop() {
            jQuery('#alarm').animate({
                scrollTop: 0
            }, 300);
        }
    });
</script>
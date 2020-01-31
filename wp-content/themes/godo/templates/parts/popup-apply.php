<div id="apply" class="popup-wrapper">
    <div class="popup-header">
        <a href="<?php bloginfo('url') ?>"><img src="<?php bloginfo('template_directory'); ?>/assets/images/logo.svg" class="logo"/></a>
        <div class="close">sluiten <span></span></div>
    </div>

    <section class="apply-section">
        <h3>Senior UX/UI Designer</h3>
        <div class="location">
            <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/map-pin-white.svg">
            Amersfoort
        </div>
    </section>

    <div class="container">
        <form>
            <p>
                <label>Naam</label>
                <input type="text" placeholder="John Doe" name="username" />
            </p>

            <p>
                <label>Email</label>
                <input type="text" placeholder="john@doe.nl" name="email" />
            </p>

            <p>
                <label>Telefoon</label>
                <input type="text" placeholder="+31 6 12345678" name="phone" />
            </p>

            <p>
                <div class="drop-zone" ondrop="upload_file(event)" ondragover="return false">
                    <div class="drag-files">
                            <p>Sleep je CV en/of Portfolio bestanden hierheen</p>
                            <p>of</p>
                            <p><input type="button" value="Blader door je bestanden" onclick="file_explorer();"></p>
                            <input type="file" class="selectfile" multiple>
                    </div>
                </div>
            </p>

            <p style="text-align: center;">en/of</p>

            <p>
                <label>Digitale CV/portfolio</label>
                <input type="text" placeholder="www.johndoe.nl" name="website" />
            </p>

            <p>
                <label>Extra toevoegingen</label>
                <textarea placeholder="Hallo GoDo, ik zou graag deze functie willen vervullen omdat..." name="additions"></textarea>
            </p>

            <p>
                <label for="gdpr" class="checkbox">
                    <input type="checkbox" id="gdpr" />
                    <span class="label">Hebben wij je toestemming om je NAW-gegevens, telefoonnummer en cv (en eventueel je portfolio) te bewaren in onze database?</span>
                </label>

                <label class="checkbox-label">
                    <input type="checkbox" name="g" />
                    <span class="custom-checkbox"></span>
                </label>
            </p>

            <p>
                <input type="submit" name="apply" value="Sollicitatie versturen"/>
            </p>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#apply .close, .open-apply').click(function (e) {
            if (jQuery('#apply').toggleClass('is-open').hasClass('is-open')) {
                jQuery('body').css('overflow', 'hidden');
                jQuery('#apply').removeClass('hidden');
            } else {
                jQuery('body').css('overflow', 'auto');
                jQuery('#apply').addClass('hidden');
            }
        });
    });
</script>
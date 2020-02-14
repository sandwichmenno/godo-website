<div id="apply" class="popup-wrapper hidden">
    <div class="popup-header">
        <a href="<?php bloginfo('url') ?>"><img src="<?php bloginfo('template_directory'); ?>/assets/images/logo.svg" class="logo"/></a>
        <div class="close">sluiten <span></span></div>
    </div>

    <section class="apply-section">
        <h3><?php echo $job['title']; ?></h3>
        <div class="location">
            <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/map-pin-white.svg">
            <?php echo $job['address']['address1']; ?>
        </div>
    </section>

    <div class="container">
        <form id="apply" action="apply" method="post" enctype="multipart/form-data">
            <p class="status"></p>
            <p>
                <label>Voornaam</label>
                <input type="text" placeholder="John" id="firstName" name="firstName" />
            </p>

            <p>
                <label>Achternaam</label>
                <input type="text" placeholder="Doe" id="lastName" name="lastName" />
            </p>

            <p>
                <label>Email</label>
                <input type="text" placeholder="john@doe.nl" id="email" name="email" />
            </p>

            <p>
                <label>Telefoon</label>
                <input type="text" placeholder="+31 6 12345678" id="phone" name="phone" />
            </p>

            <p>
                <div class="drop-zone" id="drop-zone">
                    <div class="drag-files">
                            <p>Sleep je CV en/of Portfolio bestanden hierheen</p>
                            <p>of</p>
                            <p><input type="button" value="Blader door je bestanden" onclick="file_explorer();"></p>
                            <input type="file" class="selectFile" id="file" name="file[]" multiple>
                    </div>
                </div>
            </p>

            <p style="text-align: center;">en/of</p>

            <p>
                <label>Digitale CV/portfolio/LinkedIn</label>
                <input type="text" placeholder="www.johndoe.nl" id="website" name="website" />
            </p>

            <p>
                <label>Extra toevoegingen</label>
                <textarea placeholder="Hallo GoDo, ik zou graag deze functie willen vervullen omdat..." id="additions" name="additions"></textarea>
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
    function file_explorer() {
        document.getElementById('file').click();
    }

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

        jQuery('form#apply').on('submit', function(e){
            const form_data = new FormData();
            const files = jQuery('#file')[0].files;
            form_data.append('action', 'ajaxapply');
            form_data.append('firstName', jQuery("#firstName").val());
            form_data.append('lastName', jQuery("#lastName").val());
            form_data.append('email', jQuery("#email").val());
            form_data.append('phone', jQuery("#phone").val());
            form_data.append('additions', jQuery("#additions").val() + " Digitale CV/Website/LinkedIn: " + jQuery("#website").val());

            for (let i = 0; i < files.length; i++) {
                form_data.append('file[]', files[i]);
            }

            jQuery.ajax({
                type: 'POST',
                url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                processData: false,
                contentType: false,
                data: form_data,
                success: function(response){
                    console.log(response)
                }
            });
            e.preventDefault();
        });
    });
</script>
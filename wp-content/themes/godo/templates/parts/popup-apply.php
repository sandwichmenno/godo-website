<div id="apply" class="popup-wrapper hidden">
    <div class="popup-header">
        <a href="<?php bloginfo('url') ?>"><img src="<?php bloginfo('template_directory'); ?>/assets/images/logo.svg" class="logo"/></a>
        <div class="close">sluiten <span></span></div>
    </div>

    <div id="applyForm">
        <section class="apply-section">
            <h3><?php echo $job['title']; ?></h3>
            <div class="location">
                <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/map-pin-white.svg">
                <?php echo $job['address']['address1']; ?>
            </div>
        </section>

        <div class="container gray">
            <form id="apply" action="apply" method="post" enctype="multipart/form-data">
                <span class="alert"></span>
                <p>
                    <label>Voornaam (verplicht)</label>
                    <input type="text" placeholder="John" id="firstName" name="firstName" />
                </p>

                <p>
                    <label>Achternaam (verplicht)</label>
                    <input type="text" placeholder="Doe" id="lastName" name="lastName" />
                </p>

                <p>
                    <label>Email (verplicht)</label>
                    <input type="text" placeholder="john@doe.nl" id="email" name="email" />
                </p>

                <p>
                    <label>Telefoon (verplicht)</label>
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

    <div id="successScreen" style="display: none;">
        <section class="page-section hero-wrapper">
            <div class="hero" style="background: #f4f4f4 url('<?php bloginfo('template_directory'); ?>/assets/images/heros/hero_success.jpg') no-repeat center center; background-size: cover;"></div>
            <div class="container row">
                <div class="hero-title"><h1>We hebben je sollicitatie ontvangen!</h1></div>
            </div>
        </section>

        <div class="container">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tempus dolor quis nibh interdum, vitae auctor dui cursus. Integer congue felis sed erat maximus elementum. Sed gravida fringilla elit, id tempor lorem lacinia in. Etiam id tellus in leo luctus finibus.

            <div style="margin-top: 16px; text-align: center;">
                <h2>Functie</h2>
                <span><?php echo $job['title']; ?></span>

                <h2 style="margin-top: 16px;">Locatie</h2>
                <span><?php echo $job['address']['address1']; ?></span>

                <h2 style="margin-top: 16px;">Gegevens</h2>
                <span class="userInfo"></span>
            </div>

            <p style="float: right;"><a href="" class="button primary dark" style="margin-left: auto;">Venster sluiten</a></p>
        </div>
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
            const data = {
                firstName: jQuery("#firstName").val(),
                lastName: jQuery("#lastName").val(),
                email: jQuery("#email").val(),
                phone: jQuery("#phone").val(),
                website: jQuery("#website").val(),
                additions: jQuery("#additions").val(),
            };

            form_data.append('action', 'ajaxapply');
            form_data.append('firstName', data['firstName']);
            form_data.append('lastName', data['lastName']);
            form_data.append('email', data['email']);
            form_data.append('phone', data['phone']);
            form_data.append('website', data['website']);
            form_data.append('additions', data['additions']);
            form_data.append('job', '<?php echo $job['id']; ?>');

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
                    console.log(response);
                    const res = JSON.parse(response);

                    jQuery('#apply .error').removeClass('error');
                    jQuery('#apply .errorText').remove();
                    jQuery('.alert').hide();

                    if(res.success === true) {
                        console.log('success!');
                        jQuery('#successScreen .userInfo').append('<p>' + data['firstName'] + ' ' + data['lastName'] +
                            '<br>' + data['email'] +
                            '<br>' + data['phone'] +
                            '</p>');

                        jQuery('#successScreen').show();
                        jQuery('#applyForm').remove();
                        scrollToTop();
                    } else if (res.success === "empty") {
                        jQuery.each(res.errors, function(key, value) {
                            const input = jQuery('#'+ key + '');
                            input.addClass('error');
                            input.before('<p class="errorText">' + value + '</p>');
                        });

                        scrollToTop();
                        showError('danger', "Vul alle verplichte velden in");
                    } else if (res.success === 'failed') {
                        scrollToTop();
                        showError('danger', "Er ging wat mis tijdens het verwerken van jouw gegevens. Probeer het nog een keer of neem contact op als dit probleem zich blijft voordoen.");
                    }
                },
            });

            function scrollToTop() {
                jQuery('#apply').animate({
                    scrollTop: 0
                }, 300);
            }

            function showError(type, message) {
                jQuery('.alert').show().addClass(type).text(message);
            }
            e.preventDefault();
        });
    });
</script>
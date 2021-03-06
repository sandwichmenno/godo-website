<div id="apply" class="popup-wrapper hidden">
    <div class="wrapper">
        <div class="popup-header">
            <a href="<?php bloginfo('url') ?>"><img src="<?php bloginfo('template_directory'); ?>/assets/images/logo.svg" class="logo"/></a>
            <div class="close">sluiten <span></span></div>
        </div>


        <div id="loader" style="margin-top: 64px;">
            <p>Je sollicitatie wordt verwerkt</p>
            <img src="<?php bloginfo('template_directory'); ?>/assets/images/loader.svg">
        </div>

        <div id="applyForm">
            <section class="apply-section">
                <h3><?php echo $job['title']; ?></h3>
                <div class="location">
                    <?php if($job['address']['address1']) { ?>
                        <img src="<?php bloginfo('template_directory'); ?>/assets/images/icons/map-pin-white.svg">
                        <?php echo $job['address']['address1']; ?>
                    <?php } ?>
                </div>
            </section>

            <div class="container gray">
                <form id="apply" action="apply" method="post" enctype="multipart/form-data">
                    <span class="alert"></span>
                    <p>
                        <label><?php echo $lang['vacatures']['solliciteren']['voornaam']; ?></label>
                        <input type="text" placeholder="John" id="firstName" name="firstName" />
                    </p>

                    <p>
                        <label><?php echo $lang['vacatures']['solliciteren']['achternaam']; ?></label>
                        <input type="text" placeholder="Doe" id="lastName" name="lastName" />
                    </p>

                    <p>
                        <label><?php echo $lang['vacatures']['solliciteren']['email']; ?></label>
                        <input type="text" placeholder="john@doe.nl" id="email" name="email" />
                    </p>

                    <p>
                        <label><?php echo $lang['vacatures']['solliciteren']['telefoon']; ?></label>
                        <input type="text" placeholder="+31 6 12345678" id="phone" name="phone" />
                    </p>

                    <p>
                        <div class="drop-zone" id="drop-zone">
                            <div class="drag-files">
                                    <p><?php echo $lang['vacatures']['solliciteren']['cv']; ?></p>
                                    <p><?php echo $lang['vacatures']['solliciteren']['of']; ?></p>
                                    <p><input type="button" value="<?php echo $lang['vacatures']['solliciteren']['files']; ?>" onclick="file_explorer();"></p>
                                    <input type="file" class="selectFile" id="file" name="file[]" multiple>
                            </div>
                        </div>
                    </p>

                    <div id="file-list"></div>

                    <p style="text-align: center;"><?php echo $lang['vacatures']['solliciteren']['enof']; ?></p>

                    <p>
                        <label><?php echo $lang['vacatures']['solliciteren']['website']; ?></label>
                        <input type="text" placeholder="www.johndoe.nl" id="website" name="website" />
                    </p>

                    <p>
                        <label><?php echo $lang['vacatures']['solliciteren']['motivation']; ?></label>
                        <textarea placeholder="Hi, wat een leuke vacature! Dit is echt een job voor mij omdat, …" id="additions" name="additions"></textarea>
                    </p>

                    <p>
                        <label for="gdpr" class="checkbox">
                            <input type="checkbox" id="gdpr" />
                            <span class="label"><?php echo $lang['vacatures']['solliciteren']['privacy']; ?> <a href="/privacy-statement" target="_blank">privacy statement</a></span>
                        </label>

                        <label class="checkbox-label">
                            <input type="checkbox" name="g" />
                            <span class="custom-checkbox"></span>
                        </label>
                    </p>

                    <p>
                        <input type="submit" name="apply" value="<?php echo $lang['vacatures']['solliciteren']['send']; ?>"/>
                    </p>
            </div>
        </div>

        <div id="successScreen" style="display: none;">
            <section class="page-section hero-wrapper">
                <div class="hero" style="background: #f4f4f4 url('<?php bloginfo('template_directory'); ?>/assets/images/heros/hero_success.jpg') no-repeat center center; background-size: cover;"></div>
                <div class="container row">
                    <div class="hero-title"><h1>Je hebt gesolliciteerd.</h1></div>
                </div>
            </section>

            <div class="container">
                <p>Het is gelukt! We hebben je sollicitatie ontvangen. So... what’s next? We gaan er zo snel mogelijk naar kijken. Daarna nemen we contact met je op om je sollicitatie te bespreken. Lijkt het de ideale match? Dan nodigen we je graag uit voor een kop koffie. Speak to you soon!</p>

                <div style="margin-top: 16px; text-align: center;">
                    <h2>Functie</h2>
                    <span><p><?php echo $job['title']; ?></p></span>

                    <?php if($job['address']['address1']) { ?>
                        <h2 style="margin-top: 16px;">Locatie</h2>
                        <span><p><?php echo $job['address']['address1']; ?></p></span>
                    <?php } ?>

                    <h2 style="margin-top: 16px;">Gegevens</h2>
                    <span class="userInfo"></span>
                </div>

                <p style="float: right;"><div class="button primary dark close" style="margin-left: auto;">Over en sluiten</div></p>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    let filesArray;

    function file_explorer() {
        document.getElementById('file').click();
    }

    function changeFileList(files) {
        if(files) {
            filesArray = Array.from(files);
        }

        jQuery('#file-list').empty();
        const names = jQuery.map(filesArray, function (val) { return val.name; });

        jQuery.each(names, function (i, name) {
            jQuery('#file-list').append("<div class='tag'><div onclick='removeFile("+ i +")' class='icon'><img src='<?php bloginfo('template_directory'); ?>/assets/images/icons/bin.svg'/></div>" + name + "</div>");
        });
    }

    function removeFile(file) {
        filesArray.splice(file,1);
        changeFileList();
    }

    let droppedFiles = false;
    const dropZone = jQuery('#drop-zone');

    dropZone.on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
    })
        .on('dragover dragenter', function() {
            dropZone.addClass('is-dragover');
        })
        .on('dragleave dragend drop', function() {
            dropZone.removeClass('is-dragover');
        })
        .on('drop', function(e) {
            droppedFiles = e.originalEvent.dataTransfer.files;

            changeFileList(droppedFiles);
        });

    jQuery(document).ready(function () {
        jQuery('#loader').hide();

        jQuery('#apply .close, .open-apply').click(function (e) {
            togglePopup();
        });

        jQuery(document).mouseup(function (e){
            const popupWrapper = jQuery("#apply.popup-wrapper");
            const wrapper = jQuery("#apply.popup-wrapper .wrapper");

            if(!popupWrapper.hasClass('hidden')) {
                if (!wrapper.is(e.target) && wrapper.has(e.target).length === 0) {
                    togglePopup();
                }
            }
        });

        jQuery('.selectFile').on("change", function () {
            const files = jQuery('.selectFile').prop("files");
            changeFileList(files);
        });

        jQuery('form#apply').on('submit', function(e){
            const form_data = new FormData();
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

            if (jQuery('#gdpr').prop('checked')) {
                form_data.append('comments', 'Deze gebruiker heeft WEL toestemming gegeven om zijn/haar gegevens langer te bewaren');
            } else {

                form_data.append('comments', 'Deze gebruiker heeft GEEN toestemming gegeven om zijn/haar gegevens langer te bewaren');
            }

            form_data.append('job', '<?php echo $job['id']; ?>');
            form_data.append('jobTitle', '<?php echo $job['title']; ?>');
            form_data.append('recruiterEmail', '<?php echo $recruiterEmail; ?>');
            form_data.append('url', window.location.href);

            for (let i = 0; i < filesArray.length; i++) {
                form_data.append('file[]', filesArray[i]);
            }

            for (let pair of form_data.entries()) {
                console.log(pair[1]);
            }

            jQuery.ajax({
                type: 'POST',
                url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                processData: false,
                contentType: false,
                data: form_data,
                beforeSend: function() {
                    jQuery('#loader').show();
                    jQuery('#applyForm').hide();
                },
                complete: function(){
                    jQuery('#loader').hide();
                },
                success: function(response){
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
                        jQuery('#applyForm').show();

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

        function togglePopup() {
            const applyPopup = jQuery('#apply');
            const body = jQuery('body');

            if (applyPopup.toggleClass('is-open').hasClass('is-open')) {
                body.css('overflow', 'hidden');
                applyPopup.removeClass('hidden');
            } else {
                body.css('overflow', 'auto');
                applyPopup.addClass('hidden');
            }
        }
    });
</script>
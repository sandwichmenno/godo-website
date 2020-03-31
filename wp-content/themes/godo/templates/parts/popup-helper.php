<div id="helper" class="popup-wrapper hidden">
    <div class="wrapper">
        <div class="popup-header">
            <a href="<?php bloginfo('url') ?>"><img src="<?php bloginfo('template_directory'); ?>/assets/images/logo.svg" class="logo"/></a>
            <div class="close">sluiten <span></span></div>
        </div>

        <div id="helperForm">
            <div class="chat"></div>
            <div class="response-field row"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    <?php $bh = new BullhornAPI(); ?>
    let skills = <?php echo json_encode($bh->skillsFetchAll()); ?>;

    jQuery(document).ready(function () {
        initHelper(0, skills);

        jQuery(document).on("click", "#helper .close, .open-helper", function (e) {
            if (jQuery('#helper').toggleClass('is-open').hasClass('is-open')) {
                jQuery('body').css('overflow', 'hidden');
                jQuery('#helper').removeClass('hidden');
            } else {
                jQuery('body').css('overflow', 'auto');
                jQuery('#helper').addClass('hidden');
            }
        });
    });
</script>
<div id="share" class="popup-wrapper hidden">
    <div class="wrapper">
        <div class="popup-header">
            <a href="<?php bloginfo('url') ?>"><img src="<?php bloginfo('template_directory'); ?>/assets/images/logo.svg" class="logo"/></a>
            <div class="close">sluiten <span></span></div>
        </div>

        <div class="container" style="margin-top: 32px;">
            <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('share-buttons') ) :

            endif; ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#share .close, .open-share').click(function (e) {
            togglePopup();
        });

        jQuery(document).mouseup(function (e){
            const popupWrapper = jQuery("#share.popup-wrapper");
            const wrapper = jQuery("#share.popup-wrapper .wrapper");

            if(!popupWrapper.hasClass('hidden')) {
                if (!wrapper.is(e.target) && wrapper.has(e.target).length === 0) {
                    togglePopup();
                }
            }
        });

        function togglePopup() {
            const sharePopup = jQuery('#share');
            const body = jQuery('body');

            if (sharePopup.toggleClass('is-open').hasClass('is-open')) {
                body.css('overflow', 'hidden');
                sharePopup.removeClass('hidden');
            } else {
                body.css('overflow', 'auto');
                sharePopup.addClass('hidden');
            }
        }
    });
</script>
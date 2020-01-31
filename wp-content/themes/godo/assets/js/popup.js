jQuery('.popup-wrapper .close').click(function (e) {
    console.log("closing!")
    top = jQuery(window).scrollTop();
    left = jQuery(window).scrollLeft();

    if (jQuery('.popup-wrapper').toggleClass('is-open').hasClass('is-open')) {
        jQuery('body').css('overflow', 'auto');
    } else {
        jQuery('body').css('overflow', 'hidden');
    }
});
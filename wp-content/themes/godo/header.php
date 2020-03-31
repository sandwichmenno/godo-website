<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <title><?php bloginfo( 'name' ); ?> <?php echo $pagetitle; ?></title>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php if (is_page('vacature')) { ?>
        <?php global $meta; ?>
        <meta property="og:title" content="<?php echo $meta['title'] ?>">
        <meta property="og:description" content="<?php echo $meta['description'] ?>">
        <meta property="og:url" content="<?php echo $meta['url'] ?>">
        <meta property="og:image" content="<?php echo $meta['image'] ?>">


        <meta property="title" content="<?php echo $meta['title'] ?>">
        <meta property="description" content="<?php echo $meta['description'] ?>">
        <meta content="index, follow" name="robots">
    <?php } ?>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php
        global $lang;
        $lang = getLang();
        $curlang = isset($_COOKIE["lang"]) ? $_COOKIE["lang"] : "nl";
    ?>

    <div class="page-header-wrapper">
        <header class="page-header">
            <div class="container row">
                <a href="<?php bloginfo('url') ?>"><img src="<?php bloginfo('template_directory'); ?>/assets/images/logo.svg" class="logo"/></a>

                <div class="mainMenu">
                    <?php
                    if($curlang === "nl") {
                        wp_nav_menu( array( 'theme_location' => 'header-menu' ) );
                    } else {
                        wp_nav_menu( array( 'theme_location' => 'header-menu-engels' ) );
                    }
                    ?>
                </div>

                <div class="menuToggle">
                    <div class="hamburger hamburger--squeeze">
                        <div class="hamburger-box">
                            <div class="hamburger-inner"></div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    </div>

    <div class="menu-overlay-wrapper hidden">
        <?php
            if($curlang === "nl") {
                wp_nav_menu( array( 'theme_location' => 'header-menu' ) );
            } else {
                wp_nav_menu( array( 'theme_location' => 'header-menu-engels' ) );
            }
        ?>
    </div>

    <script type="text/javascript">
        let isMobile = false;

        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            isMobile = true;
        }

            jQuery(document).ready(function () {
                if(isMobile) {
                    let top;
                    let left;

                    jQuery('.hamburger').click(function (e) {
                        top = jQuery(window).scrollTop();
                        left = jQuery(window).scrollLeft();

                        if (jQuery('.hamburger').toggleClass('is-active').hasClass('is-active')) {
                            openModal();
                        } else {
                            closeModal();
                        }
                    });
                }

                let prev = 0;
                const $window = jQuery(window);
                const nav = jQuery('.page-header-wrapper');

                $window.on('scroll', function(){
                    let scrollTop = $window.scrollTop();
                    if(scrollTop > prev && scrollTop > 0) {
                        nav.addClass('hidden');
                    } else {
                        nav.removeClass('hidden');
                    }
                    prev = scrollTop;
                });
            });

            if(isMobile) {
                function closeModal() {
                    console.log("Closed!");
                    jQuery('.menu-overlay-wrapper').addClass('hidden');
                    jQuery('.page-header-wrapper').css('background-color', '#ffffff');
                    jQuery('body').css('overflow', 'auto');
                }

                function openModal() {
                    console.log("Opened!");
                    jQuery('.menu-overlay-wrapper').removeClass('hidden');
                    jQuery('.page-header-wrapper').css('background-color', '#f4f4f4');
                    jQuery('body').css('overflow', 'hidden');
                }
            }
    </script>
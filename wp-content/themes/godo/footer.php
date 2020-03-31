<?php
    global $lang;
    $curlang = isset($_COOKIE["lang"]) ? $_COOKIE["lang"] : "nl";
?>
        <div class="page-footer-wrapper">
            <footer class="page-footer">
                <div class="container">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/logo.svg" class="logo"/>

                    <div class="page-footer-content row">
                        <?php
                        if($curlang === "nl") {
                            wp_nav_menu( array( 'theme_location' => 'footer-menu' ) );
                        } else {
                            wp_nav_menu( array( 'theme_location' => 'footer-menu-engels' ) );
                        }
                        ?>

                        <div class="info">
                            <form action="" class="language-picker">
                                <select name="language-picker-select" id="language-picker-select">
                                    <option lang="de" value="en" <?= isset($_COOKIE["lang"]) ? ($_COOKIE["lang"] === "en") ? "selected" : "" : "" ?>><?php echo $lang['footer']['taal']['en']; ?></option>
                                    <option lang="en" value="nl" <?= isset($_COOKIE["lang"]) ? ($_COOKIE["lang"] === "nl") ? "selected" : "" : "selected" ?>><?php echo $lang['footer']['taal']['nl']; ?></option>
                                </select>
                            </form>

                            Fred. Roeskestraat 99
                            <br/>1076 EE Amsterdam
                            <br/>info@godo.nl
                        </div>
                    </div>

                </div>

                <div class="lower-bar"><?php echo $lang['footer']['bottom']; ?> <a href="https://makerstreet.nl/" target="_blank">MakerStreet</a></div>
            </footer>
        </div>

        <script type="text/javascript">
            jQuery("#language-picker-select").on('change', function() {
                const value = jQuery(this).val();
                Cookies.set('lang', value);
                jQuery(window).scrollTop(0);
                window.location.reload(true);
            });
        </script>
        <?php wp_footer(); ?>
    </body>
</html>
        <div class="page-footer-wrapper">
            <footer class="page-footer">
                <div class="container">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/logo.svg" class="logo"/>

                    <div class="page-footer-content row">
                        <?php wp_nav_menu( array( 'theme_location' => 'footer-menu' ) ); ?>

                        <div class="info">
                            Fred. Roeskestraat 99
                            <br/>1076 EE Amsterdam
                            <br/>info@godo.nl
                        </div>
                    </div>

                </div>
            </footer>
        </div>
        <?php wp_footer(); ?>
    </body>
</html>
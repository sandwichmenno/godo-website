        <div class="page-footer-wrapper">
            <footer class="page-footer">
                <div class="container">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/logo.svg" class="logo"/>

                    <div class="page-footer-content row">
                        <?php wp_nav_menu( array( 'theme_location' => 'footer-menu' ) ); ?>

                        <div class="info">
                            Generaal Vetterstraat 82
                            <br/>1059 BW Amsterdam
                            <br/>info@godo.nl
                        </div>
                    </div>

                </div>
            </footer>
        </div>
        <?php wp_footer(); ?>
    </body>
</html>
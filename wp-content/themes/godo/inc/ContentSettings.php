<?php

function content_options_add(){
    register_setting( 'content_settings', 'content_settings' );
}

function add_content_options() {
    add_menu_page( __( 'Content' ), __( 'Content' ), 'manage_options', 'content_settings', '', 'dashicons-media-document', 60);
    add_submenu_page( 'content_settings', __( 'Frontpage' ), __( 'Frontpage' ), 'manage_options', 'content_frontpage_settings', 'content_frontpage_page');
    add_submenu_page( 'content_settings', __( 'About' ), __( 'About' ), 'manage_options', 'content_about_settings', 'content_about_page');
    add_submenu_page( 'content_settings', __( 'Contact' ), __( 'Contact' ), 'manage_options', 'content_contact_settings', 'content_contact_page');
    add_submenu_page( 'content_settings', __( 'Vacatures' ), __( 'Vacatures' ), 'manage_options', 'content_vacatures_settings', 'content_vacatures_page');
    add_submenu_page( 'content_settings', __( 'Footer' ), __( 'Footer' ), 'manage_options', 'content_footer_settings', 'content_footer_page');
}
add_action( 'admin_init', 'content_options_add' );
add_action( 'admin_menu', 'add_content_options' );

function content_frontpage_page() {
    global $langs;
?>
    <div class="wrap">
        <form id="lang" method="post" action="/">
            <h1>Frontpage content</h1>
            <div id="alert"></div>
            <input name="submit" id="submit" class="button button-primary" value="Wijzigingen opslaan" type="submit">

            <?php
                $active_tab = isset($_GET['tab']) && !empty($_GET['tab']) ? $_GET['tab'] : "nl-content";
            ?>

            <h2 class="nav-tab-wrapper">
                <!-- when tab buttons are clicked we jump back to the same page but with a new parameter that represents the clicked tab. accordingly we make it active -->
                <a href="?page=content_frontpage_settings&tab=nl-content" class="nav-tab <?php if($active_tab == 'nl-content'){echo 'nav-tab-active';} ?> ">Nederlands</a>
                <a href="?page=content_frontpage_settings&tab=en-content" class="nav-tab <?php if($active_tab == 'en-content'){echo 'nav-tab-active';} ?>">Engels</a>
            </h2>

            <?php if($active_tab === "nl-content") { ?>
                <?php foreach($langs['nl']['frontpage'] as $section => $content) { ?>
                    <h2 class="title"><?php echo $section; ?></h2>
                        <?php foreach($content as $item => $text) { ?>
                            <p style="font-weight: bold;"><?php echo $item; ?></p>
                            <textarea name="<?php echo $section; ?>" id="<?php echo $item; ?>" data-lang="nl" class="large-text langInput" rows="3"><?php echo $text; ?></textarea>
                        <?php } ?>
                <?php } ?>
            <?php } ?>

            <?php if($active_tab === "en-content") { ?>
                <?php foreach($langs['en']['frontpage'] as $section => $content) { ?>
                    <h2 class="title"><?php echo $section; ?></h2>
                    <?php foreach($content as $item => $text) { ?>
                        <p style="font-weight: bold;"><?php echo $item; ?></p>
                        <textarea name="<?php echo $section; ?>" id="<?php echo $item; ?>" data-lang="en" class="large-text langInput" rows="3"><?php echo $text; ?></textarea>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            <p class="submit"><input name="submit" id="submit" class="button button-primary" value="Wijzigingen opslaan" type="submit"></p>
            <div id="loader" style="display: none;"><img src="<?php bloginfo('template_directory'); ?>/assets/images/loader.svg"></div>
        </form>

    </div>

    <script type="text/javascript">
        jQuery('#lang').on('submit', function(e){
            event.preventDefault();
            let obj = {};
            let lang = "";

            jQuery('#alert').empty();

            jQuery('.langInput').each(function() {
                const input = jQuery(this);
                const section = input.attr('name');
                const element = input.attr('id');

                lang = input.data('lang');

                obj[section] = {...obj[section], [element]: input.val()};
            });

            jQuery.ajax({
                type: 'POST',
                url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                dataType: "json",
                data: {action: "saveLang", lang: lang, page: "frontpage", data: obj},
                beforeSend: function() {
                    jQuery('#loader').show();
                    jQuery('#submit').hide();
                },
                complete: function() {
                    jQuery('#loader').hide();
                    jQuery('#submit').show();
                },
                success: function(response){
                    jQuery(window).scrollTop(0);

                    if(response['success']) {
                        jQuery('#alert').html('<div class="updated notice"><p>Content is succesvol aangepast!</p></div>');
                    } else {
                        jQuery('#alert').html('<div class="error notice"><p>Er ging iets fout. Probeer het opnieuw.</p></div>');
                    }
                },
            });
        });
    </script>
    <?php
}
function content_about_page() {
    global $langs;
    ?>
    <div class="wrap">
        <form id="lang" method="post" action="/">
            <h1>About content</h1>
            <div id="alert"></div>
            <input name="submit" id="submit" class="button button-primary" value="Wijzigingen opslaan" type="submit">

            <?php
                $active_tab = isset($_GET['tab']) && !empty($_GET['tab']) ? $_GET['tab'] : "nl-content";
            ?>

            <h2 class="nav-tab-wrapper">
                <!-- when tab buttons are clicked we jump back to the same page but with a new parameter that represents the clicked tab. accordingly we make it active -->
                <a href="?page=content_about_settings&tab=nl-content" class="nav-tab <?php if($active_tab == 'nl-content'){echo 'nav-tab-active';} ?> ">Nederlands</a>
                <a href="?page=content_about_settings&tab=en-content" class="nav-tab <?php if($active_tab == 'en-content'){echo 'nav-tab-active';} ?>">Engels</a>
            </h2>

            <?php if($active_tab === "nl-content") { ?>
                <?php foreach($langs['nl']['about'] as $section => $content) { ?>
                    <h2 class="title"><?php echo $section; ?></h2>
                    <?php foreach($content as $item => $text) { ?>
                        <p style="font-weight: bold;"><?php echo $item; ?></p>
                        <textarea name="<?php echo $section; ?>" id="<?php echo $item; ?>" data-lang="nl" class="large-text langInput" rows="3"><?php echo $text; ?></textarea>
                    <?php } ?>
                <?php } ?>
            <?php } ?>

            <?php if($active_tab === "en-content") { ?>
                <?php foreach($langs['en']['about'] as $section => $content) { ?>
                    <h2 class="title"><?php echo $section; ?></h2>
                    <?php foreach($content as $item => $text) { ?>
                        <p style="font-weight: bold;"><?php echo $item; ?></p>
                        <textarea name="<?php echo $section; ?>" id="<?php echo $item; ?>" data-lang="en" class="large-text langInput" rows="3"><?php echo $text; ?></textarea>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            <p class="submit"><input name="submit" id="submit" class="button button-primary" value="Wijzigingen opslaan" type="submit"></p>
            <div id="loader" style="display: none;"><img src="<?php bloginfo('template_directory'); ?>/assets/images/loader.svg"></div>
        </form>

    </div>

    <script type="text/javascript">
        jQuery('#lang').on('submit', function(e){
            event.preventDefault();
            let obj = {};
            let lang = "";

            jQuery('#alert').empty();

            jQuery('.langInput').each(function() {
                const input = jQuery(this);
                const section = input.attr('name');
                const element = input.attr('id');

                lang = input.data('lang');

                obj[section] = {...obj[section], [element]: input.val()};
            });

            jQuery.ajax({
                type: 'POST',
                url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                dataType: "json",
                data: {action: "saveLang", lang: lang, page: "about", data: obj},
                beforeSend: function() {
                    jQuery('#loader').show();
                    jQuery('#submit').hide();
                },
                complete: function() {
                    jQuery('#loader').hide();
                    jQuery('#submit').show();
                },
                success: function(response){
                    jQuery(window).scrollTop(0);

                    if(response['success']) {
                        jQuery('#alert').html('<div class="updated notice"><p>Content is succesvol aangepast!</p></div>');
                    } else {
                        jQuery('#alert').html('<div class="error notice"><p>Er ging iets fout. Probeer het opnieuw.</p></div>');
                    }
                },
            });
        });
    </script>
    <?php
}
function content_contact_page() {
    global $langs;
    ?>
    <div class="wrap">
        <form id="lang" method="post" action="/">
            <h1>Contact content</h1>
            <div id="alert"></div>
            <input name="submit" id="submit" class="button button-primary" value="Wijzigingen opslaan" type="submit">

            <?php
            $active_tab = isset($_GET['tab']) && !empty($_GET['tab']) ? $_GET['tab'] : "nl-content";
            ?>

            <h2 class="nav-tab-wrapper">
                <!-- when tab buttons are clicked we jump back to the same page but with a new parameter that represents the clicked tab. accordingly we make it active -->
                <a href="?page=content_contact_settings&tab=nl-content" class="nav-tab <?php if($active_tab == 'nl-content'){echo 'nav-tab-active';} ?> ">Nederlands</a>
                <a href="?page=content_contact_settings&tab=en-content" class="nav-tab <?php if($active_tab == 'en-content'){echo 'nav-tab-active';} ?>">Engels</a>
            </h2>

            <?php if($active_tab === "nl-content") { ?>
                <?php foreach($langs['nl']['contact'] as $section => $content) { ?>
                    <h2 class="title"><?php echo $section; ?></h2>
                    <?php foreach($content as $item => $text) { ?>
                        <p style="font-weight: bold;"><?php echo $item; ?></p>
                        <textarea name="<?php echo $section; ?>" id="<?php echo $item; ?>" data-lang="nl" class="large-text langInput" rows="3"><?php echo $text; ?></textarea>
                    <?php } ?>
                <?php } ?>
            <?php } ?>

            <?php if($active_tab === "en-content") { ?>
                <?php foreach($langs['en']['contact'] as $section => $content) { ?>
                    <h2 class="title"><?php echo $section; ?></h2>
                    <?php foreach($content as $item => $text) { ?>
                        <p style="font-weight: bold;"><?php echo $item; ?></p>
                        <textarea name="<?php echo $section; ?>" id="<?php echo $item; ?>" data-lang="en" class="large-text langInput" rows="3"><?php echo $text; ?></textarea>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            <p class="submit"><input name="submit" id="submit" class="button button-primary" value="Wijzigingen opslaan" type="submit"></p>
            <div id="loader" style="display: none;"><img src="<?php bloginfo('template_directory'); ?>/assets/images/loader.svg"></div>
        </form>

    </div>

    <script type="text/javascript">
        jQuery('#lang').on('submit', function(e){
            event.preventDefault();
            let obj = {};
            let lang = "";

            jQuery('#alert').empty();

            jQuery('.langInput').each(function() {
                const input = jQuery(this);
                const section = input.attr('name');
                const element = input.attr('id');

                lang = input.data('lang');

                obj[section] = {...obj[section], [element]: input.val()};
            });

            jQuery.ajax({
                type: 'POST',
                url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                dataType: "json",
                data: {action: "saveLang", lang: lang, page: "contact", data: obj},
                beforeSend: function() {
                    jQuery('#loader').show();
                    jQuery('#submit').hide();
                },
                complete: function() {
                    jQuery('#loader').hide();
                    jQuery('#submit').show();
                },
                success: function(response){
                    jQuery(window).scrollTop(0);

                    if(response['success']) {
                        jQuery('#alert').html('<div class="updated notice"><p>Content is succesvol aangepast!</p></div>');
                    } else {
                        jQuery('#alert').html('<div class="error notice"><p>Er ging iets fout. Probeer het opnieuw.</p></div>');
                    }
                },
            });
        });
    </script>
    <?php
}
function content_vacatures_page() {
    global $langs;
    ?>
    <div class="wrap">
        <form id="lang" method="post" action="/">
            <h1>Vacatures content</h1>
            <div id="alert"></div>
            <input name="submit" id="submit" class="button button-primary" value="Wijzigingen opslaan" type="submit">

            <?php
            $active_tab = isset($_GET['tab']) && !empty($_GET['tab']) ? $_GET['tab'] : "nl-content";
            ?>

            <h2 class="nav-tab-wrapper">
                <!-- when tab buttons are clicked we jump back to the same page but with a new parameter that represents the clicked tab. accordingly we make it active -->
                <a href="?page=content_vacatures_settings&tab=nl-content" class="nav-tab <?php if($active_tab == 'nl-content'){echo 'nav-tab-active';} ?> ">Nederlands</a>
                <a href="?page=content_vacatures_settings&tab=en-content" class="nav-tab <?php if($active_tab == 'en-content'){echo 'nav-tab-active';} ?>">Engels</a>
            </h2>

            <?php if($active_tab === "nl-content") { ?>
                <?php foreach($langs['nl']['vacatures'] as $section => $content) { ?>
                    <h2 class="title"><?php echo $section; ?></h2>
                    <?php foreach($content as $item => $text) { ?>
                        <p style="font-weight: bold;"><?php echo $item; ?></p>
                        <textarea name="<?php echo $section; ?>" id="<?php echo $item; ?>" data-lang="nl" class="large-text langInput" rows="3"><?php echo $text; ?></textarea>
                    <?php } ?>
                <?php } ?>
            <?php } ?>

            <?php if($active_tab === "en-content") { ?>
                <?php foreach($langs['en']['vacatures'] as $section => $content) { ?>
                    <h2 class="title"><?php echo $section; ?></h2>
                    <?php foreach($content as $item => $text) { ?>
                        <p style="font-weight: bold;"><?php echo $item; ?></p>
                        <textarea name="<?php echo $section; ?>" id="<?php echo $item; ?>" data-lang="en" class="large-text langInput" rows="3"><?php echo $text; ?></textarea>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            <p class="submit"><input name="submit" id="submit" class="button button-primary" value="Wijzigingen opslaan" type="submit"></p>
            <div id="loader" style="display: none;"><img src="<?php bloginfo('template_directory'); ?>/assets/images/loader.svg"></div>
        </form>

    </div>

    <script type="text/javascript">
        jQuery('#lang').on('submit', function(e){
            event.preventDefault();
            let obj = {};
            let lang = "";

            jQuery('#alert').empty();

            jQuery('.langInput').each(function() {
                const input = jQuery(this);
                const section = input.attr('name');
                const element = input.attr('id');

                lang = input.data('lang');

                obj[section] = {...obj[section], [element]: input.val()};
            });

            jQuery.ajax({
                type: 'POST',
                url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                dataType: "json",
                data: {action: "saveLang", lang: lang, page: "vacatures", data: obj},
                beforeSend: function() {
                    jQuery('#loader').show();
                    jQuery('#submit').hide();
                },
                complete: function() {
                    jQuery('#loader').hide();
                    jQuery('#submit').show();
                },
                success: function(response){
                    jQuery(window).scrollTop(0);

                    if(response['success']) {
                        jQuery('#alert').html('<div class="updated notice"><p>Content is succesvol aangepast!</p></div>');
                    } else {
                        jQuery('#alert').html('<div class="error notice"><p>Er ging iets fout. Probeer het opnieuw.</p></div>');
                    }
                },
            });
        });
    </script>
    <?php
}
function content_footer_page() {
    global $langs;
    ?>
    <div class="wrap">
        <form id="lang" method="post" action="/">
            <h1>Footer content</h1>
            <div id="alert"></div>
            <input name="submit" id="submit" class="button button-primary" value="Wijzigingen opslaan" type="submit">

            <?php
            $active_tab = isset($_GET['tab']) && !empty($_GET['tab']) ? $_GET['tab'] : "nl-content";
            ?>

            <h2 class="nav-tab-wrapper">
                <!-- when tab buttons are clicked we jump back to the same page but with a new parameter that represents the clicked tab. accordingly we make it active -->
                <a href="?page=content_footer_settings&tab=nl-content" class="nav-tab <?php if($active_tab == 'nl-content'){echo 'nav-tab-active';} ?> ">Nederlands</a>
                <a href="?page=content_footer_settings&tab=en-content" class="nav-tab <?php if($active_tab == 'en-content'){echo 'nav-tab-active';} ?>">Engels</a>
            </h2>

            <?php if($active_tab === "nl-content") { ?>
                <?php foreach($langs['nl']['footer'] as $section => $content) { ?>
                    <h2 class="title"><?php echo $section; ?></h2>
                    <?php foreach($content as $item => $text) { ?>
                        <p style="font-weight: bold;"><?php echo $item; ?></p>
                        <textarea name="<?php echo $section; ?>" id="<?php echo $item; ?>" data-lang="nl" class="large-text langInput" rows="3"><?php echo $text; ?></textarea>
                    <?php } ?>
                <?php } ?>
            <?php } ?>

            <?php if($active_tab === "en-content") { ?>
                <?php foreach($langs['en']['footer'] as $section => $content) { ?>
                    <h2 class="title"><?php echo $section; ?></h2>
                    <?php foreach($content as $item => $text) { ?>
                        <p style="font-weight: bold;"><?php echo $item; ?></p>
                        <textarea name="<?php echo $section; ?>" id="<?php echo $item; ?>" data-lang="en" class="large-text langInput" rows="3"><?php echo $text; ?></textarea>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            <p class="submit"><input name="submit" id="submit" class="button button-primary" value="Wijzigingen opslaan" type="submit"></p>
            <div id="loader" style="display: none;"><img src="<?php bloginfo('template_directory'); ?>/assets/images/loader.svg"></div>
        </form>

    </div>

    <script type="text/javascript">
        jQuery('#lang').on('submit', function(e){
            event.preventDefault();
            let obj = {};
            let lang = "";

            jQuery('#alert').empty();

            jQuery('.langInput').each(function() {
                const input = jQuery(this);
                const section = input.attr('name');
                const element = input.attr('id');

                lang = input.data('lang');

                obj[section] = {...obj[section], [element]: input.val()};
            });

            jQuery.ajax({
                type: 'POST',
                url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                dataType: "json",
                data: {action: "saveLang", lang: lang, page: "footer", data: obj},
                beforeSend: function() {
                    jQuery('#loader').show();
                    jQuery('#submit').hide();
                },
                complete: function() {
                    jQuery('#loader').hide();
                    jQuery('#submit').show();
                },
                success: function(response){
                    jQuery(window).scrollTop(0);

                    if(response['success']) {
                        jQuery('#alert').html('<div class="updated notice"><p>Content is succesvol aangepast!</p></div>');
                    } else {
                        jQuery('#alert').html('<div class="error notice"><p>Er ging iets fout. Probeer het opnieuw.</p></div>');
                    }
                },
            });
        });
    </script>
    <?php
}
?>
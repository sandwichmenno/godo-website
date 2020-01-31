<?php
require_once(get_template_directory() . '/inc/bullhorn-settings.php');
require_once(get_template_directory() . '/inc/bullhorn-connect.php');
require_once(get_template_directory() . '/inc/bullhorn-endpoints.php');

if ( ! function_exists( 'godo_setup' ) ) :
    function godo_setup() {
        load_theme_textdomain( 'godo', get_template_directory() . '/languages' );
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'post-thumbnails' );
        add_filter('show_admin_bar', '__return_false');
        remove_filter( 'the_content', 'wpautop' );

        register_nav_menus( array(
            'header-menu'   => __( 'Main Menu', 'godo' ),
            'footer-menu'   => __( 'Footer Menu', 'godo' )
        ) );

        add_theme_support( 'post-formats', array ( 'aside', 'gallery', 'quote', 'image', 'video' ) );
    }
endif;
add_action( 'after_setup_theme', 'godo_setup' );

function register_styles() {
    $theme_version = wp_get_theme()->get( 'Version' );
    wp_enqueue_style( 'style', get_stylesheet_uri(), array(), $theme_version );
    wp_enqueue_style( 'hamburger', get_template_directory_uri() . '/assets/css/hamburger.css', array(), $theme_version );
    wp_enqueue_style( 'google_fonts', 'https://fonts.googleapis.com/css?family=Suez+One' );
    wp_enqueue_style( 'slickCSS', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', array(), $theme_version );
    wp_enqueue_style( 'slickThemeCSS', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css', array(), $theme_version );
}
add_action( 'wp_enqueue_scripts', 'register_styles' );

function register_scripts() {
    $theme_version = wp_get_theme()->get( 'Version' );
    wp_enqueue_script( 'jquery', array(), $theme_version, false );
    wp_enqueue_script( 'slickJS', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js' ,array(), $theme_version, false );
    wp_enqueue_script( 'popupJS', get_template_directory_uri() . '/assets/js/popup.js', $theme_version, false );
    wp_enqueue_script( 'helperJS', get_template_directory_uri() . '/assets/js/helper.js', $theme_version, false );
}
add_action( 'wp_enqueue_scripts', 'register_scripts' );

function custom_post_types() {
    $team_labels = array(
        'name'                => _x( 'Teamleden', 'Post Type General Name', 'text_domain' ),
        'singular_name'       => _x( 'Teamlid', 'Post Type Singular Name', 'text_domain' ),
        'all_items'           => __( 'Alle teamleden', 'text_domain' ),
        'view_item'           => __( 'Bekijk teamlid', 'text_domain' ),
        'add_new'             => __( 'Teamlid toevoegen', 'text_domain' ),
        'add_new_item'        => __( 'Nieuw teamlid toevoegen', 'text_domain' ),
        'edit_item'           => __( 'Teamlid wijzigen', 'text_domain' ),
        'update_item'         => __( 'Teamlid updaten', 'text_domain' ),
        'search_items'        => __( 'Teamlid zoeken', 'text_domain' ),
    );

    $team_args = array(
        'labels' => $team_labels,
        'public' => true,
        'has_archive' => false,
        'rewrite' => array('slug' => 'members'),
        'menu_icon' => 'dashicons-groups',
        'register_meta_box_cb' => 'add_metaboxes',
        'supports' => array( 'title', 'editor', 'thumbnail' ),
    );
    register_post_type( 'members', $team_args );

    $client_labels = array(
        'name'                => _x( 'Klanten', 'Post Type General Name', 'text_domain' ),
        'singular_name'       => _x( 'Klant', 'Post Type Singular Name', 'text_domain' ),
        'all_items'           => __( 'Alle klanten', 'text_domain' ),
        'view_item'           => __( 'Bekijk klanten', 'text_domain' ),
        'add_new'             => __( 'Klant toevoegen', 'text_domain' ),
        'add_new_item'        => __( 'Nieuwe klant toevoegen', 'text_domain' ),
        'edit_item'           => __( 'Klant wijzigen', 'text_domain' ),
        'update_item'         => __( 'Klant updaten', 'text_domain' ),
        'search_items'        => __( 'Klant zoeken', 'text_domain' ),
    );

    $client_args = array(
        'labels' => $client_labels,
        'public' => true,
        'has_archive' => false,
        'rewrite' => array('slug' => 'clients'),
        'menu_icon' => 'dashicons-portfolio',
        'supports' => array( 'title', 'thumbnail' ),
    );
    register_post_type( 'clients', $client_args );
}
add_action( 'init', 'custom_post_types' );

function add_metaboxes() {
    add_meta_box(
        'team_details',           // Unique ID
        'Details',  // Box title
        'build_member_meta_box',  // Content callback, must be of type callable
        'members',
        'normal', // $context
        'high' // $priority
    );
}
add_action('add_meta_boxes', 'add_metaboxes');

function build_member_meta_box( $post ){
    // make sure the form request comes from WordPress
    wp_nonce_field( basename( __FILE__ ), 'member_meta_box_nonce' );
    // retrieve the _food_cholesterol current value
    $cur_linkedin = get_post_meta( $post->ID, '__linkedin', true );
    $cur_email = get_post_meta( $post->ID, '__email', true );
    $cur_phone = get_post_meta( $post->ID, '__phone', true );
    $cur_specialties = ( get_post_meta( $post->ID, '__specialty', true ) ) ? get_post_meta( $post->ID, '__specialty', true ) : array();

    $specialties = array( 'Agile', 'Design', 'Development', 'Infra' );
    ?>
        <p>
            <label for="linkedin">LinkedIn URL</label>
            <input type="text" name="linkedin" value="<?php echo $cur_linkedin; ?>" class="widefat"/>
        </p>

        <p>
            <label for="email">Email</label>
            <input type="text" name="email" value="<?php echo $cur_email; ?>" class="widefat"/>
        </p>

        <p>
            <label for="phone">Telefoonnummer</label>
            <input type="text" name="phone" value="<?php echo $cur_phone; ?>" class="widefat"/>
        </p>

        <p>
            <label for="phone">Specialiteit(en)</label><br/>
            <?php
            foreach ( $specialties as $specialty ) {
                ?>
                <input type="checkbox" name="specialties[]" value="<?php echo $specialty; ?>" <?php checked( ( in_array( $specialty, $cur_specialties ) ) ? $specialty : '', $specialty ); ?> /><?php echo $specialty; ?> <br />
                <?php
            }
            ?>
        </p>
    <?php
}

function save_member_meta_box( $post_id ){
    // verify taxonomies meta box nonce
	if ( !isset( $_POST['member_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['member_meta_box_nonce'], basename( __FILE__ ) ) ){
        return;
    }
	// return if autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
        return;
    }
	// Check the user's permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ){
        return;
    }
	// store custom fields values
	if ( isset( $_REQUEST['linkedin'] ) ) {
        update_post_meta( $post_id, '__linkedin', sanitize_text_field( $_POST['linkedin'] ) );
    }

    if ( isset( $_REQUEST['email'] ) ) {
        update_post_meta( $post_id, '__email', sanitize_text_field( $_POST['email'] ) );
    }

    if ( isset( $_REQUEST['phone'] ) ) {
        update_post_meta( $post_id, '__phone', sanitize_text_field( $_POST['phone'] ) );
    }

    if( isset( $_POST['specialties'] ) ){
        $specialties = (array) $_POST['specialties'];
        update_post_meta( $post_id, '__specialty', $specialties );
    }else{
        // delete data
        delete_post_meta( $post_id, '__specialty' );
    }
}
add_action( 'save_post_members', 'save_member_meta_box' );

function register_widgets() {
    register_sidebar( array(
        'name' => __( 'Contact Form', 'textdomain' ),
        'id' => 'contact-form',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
    ) );
}
add_action( 'widgets_init', 'register_widgets' );

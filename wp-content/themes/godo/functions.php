<?php
require_once(get_template_directory() . '/inc/BullhornSettings.php');
require_once(get_template_directory() . '/inc/ThemeSettings.php');
require_once(get_template_directory() . '/inc/BullhornAPI.php');

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

function ajax_apply(){
    $bullhorn = new BullhornAPI();
    $post_data = $_POST;
    $candidate_files = $_FILES['file'];
    $errors = $bullhorn->validateSubmission($post_data, $candidate_files);
    $failed = false;

    //Validate post data
    if(NULL === $errors) :
        //Find duplicate candidates
        $duplicate_candidate = $bullhorn->findCandidate(array('first_name' => $post_data['firstName'], 'last_name' => $post_data['lastName'], 'email' => $post_data['email']));
        if(FALSE === $duplicate_candidate) : $failed = true; endif;

        if(FALSE === $failed) :
            if(NULL === $duplicate_candidate) :
                //Create new candidate
                $candidate_data = array(
                    'firstName' => $post_data['firstName'],
                    'lastName' => $post_data['lastName'],
                    'name' => trim($post_data['firstName'].' '.$post_data['lastName']),
                    'description' => $post_data['website'],
                    'email' => $post_data['email'],
                    'mobile' => $post_data['phone'],
                );

                $candidate = $bullhorn->createCandidate($candidate_data, $candidate_files);
                if(FALSE === $candidate) : $failed = true; endif;
            else :
                $candidate = $duplicate_candidate['id'];
            endif;
        endif;

        if(FALSE === $failed) :
            //Add candidate to Job Submission
            $submission = $bullhorn->addJobSubmission($candidate, $post_data['job'], $post_data['additions']);
            if(FALSE === $submission) : $failed = true; endif;
        endif;

        if(FALSE === $failed ) :
            echo json_encode(array('success' => true));
        else :
            echo json_encode(array('success' => 'failed'));
        endif;
    else :
        echo json_encode(array_merge(array('success' => 'empty'), array("errors" => $errors)));
    endif;

    wp_die();
}
add_action( 'wp_ajax_ajaxapply', 'ajax_apply' );
add_action( 'wp_ajax_nopriv_ajaxapply', 'ajax_apply' );

function getJobs() {
    $bullhorn = new BullhornAPI();
    echo json_encode($bullhorn->jobsFetchAll());

    wp_die();
}
add_action( 'wp_ajax_getJobs', 'getJobs' );
add_action( 'wp_ajax_nopriv_getJobs', 'getJobs' );

function getJob($id) {
    $bullhorn = new BullhornAPI();
    return $bullhorn->jobFetch($id);
}

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
    $cur_subtitle = get_post_meta( $post->ID, '__subtitle', true );
    $cur_listingDesc = get_post_meta( $post->ID, '__listingDesc', true );
    $cur_visibility = get_post_meta( $post->ID, '__visibility', true );

    $specialties = array( 'agile', 'design', 'development', 'infra' );
    ?>
        <p>
            <label for="linkedin">Functietitel / Ondertitel</label>
            <input type="text" name="subtitle" value="<?php echo $cur_subtitle; ?>" class="widefat"/>
        </p>

        <p>
            <label for="phone">Informatietekst bij vacatures</label>
            <textarea name="listingDesc" class="widefat"><?php echo $cur_listingDesc; ?></textarea>
        </p>

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
            <label for="phone">Verbergen op contactpagina</label>
            <input type="checkbox" name="visibility" value="true" <?php if($cur_visibility === "true") { echo 'checked'; } else { echo ''; } ?> class="widefat"/>
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
    if ( isset( $_REQUEST['listingDesc'] ) ) {
        update_post_meta( $post_id, '__listingDesc', sanitize_text_field( $_POST['listingDesc'] ) );
    }


    if ( isset( $_REQUEST['linkedin'] ) ) {
        update_post_meta( $post_id, '__linkedin', sanitize_text_field( $_POST['linkedin'] ) );
    }

    if ( isset( $_REQUEST['subtitle'] ) ) {
        update_post_meta( $post_id, '__subtitle', sanitize_text_field( $_POST['subtitle'] ) );
    }

    if ( isset( $_REQUEST['email'] ) ) {
        update_post_meta( $post_id, '__email', sanitize_text_field( $_POST['email'] ) );
    }

    if ( isset( $_REQUEST['phone'] ) ) {
        update_post_meta( $post_id, '__phone', sanitize_text_field( $_POST['phone'] ) );
    }

    if ( isset( $_REQUEST['visibility'] ) ) {
        update_post_meta( $post_id, '__visibility', sanitize_text_field( $_POST['visibility'] ) );
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

    register_sidebar( array(
        'name' => __( 'Deelknoppen', 'textdomain' ),
        'id' => 'share-buttons',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
    ) );
}
add_action( 'widgets_init', 'register_widgets' );


function BullhornAuthenticate() {
    $bullhorn = new BullhornAPI();
    $result = $bullhorn->bullhorn_authenticate();

    update_option('bullhorn_access_token', $result[0]);
    update_option('bullhorn_auth_token', $result[1]);
    update_option('bullhorn_refresh_token', $result[2]);
    update_option('bullhorn_rest_token', $result[3]);

    wp_redirect(admin_url( 'admin.php?page=settings'));
}
add_action( 'admin_post_bullhorn_authenticate', 'BullhornAuthenticate' );

function BullhornApply() {
    $bullhorn = new BullhornAPI();
    $candidate_data = $_POST;
    $form_status = '';

    # Validate resume upload
    if(NULL === $form_status) :
        if($_FILES) :
            if(FALSE === ($resume = validateUpload('resume'))) :
                $form_status = array('status' => 'error', 'message' => uploadError());
                unset($resume);
            endif;
        endif;

        echo 'nope';
    endif;

    //wp_redirect('http://192.168.99.100:8000/vacature/?id=15');
    exit;
}
add_action( 'admin_post_bullhorn_apply', 'BullhornApply' );

function getJobCategory($job) {
    $jobTypes = ['design', 'dev', 'agile', 'infra', 'support'];
    $catName = !empty($job['categories']['data'][0]['name']) ? $job['categories']['data'][0]['name'] : '';
    $category = '';

    foreach ($jobTypes as $jobType) {
        if(stripos($catName, $jobType) !== FALSE ) {
            $category = $jobType;
        }
    }

    if(empty($category)) { $category = 'misc'; }

    return $category;
}

function getRecruiterBySpecialism($category) {
    $args = array(
        'post_type' => 'members',
    );
    $your_loop = new WP_Query( $args );

    if ( $your_loop->have_posts() ) : while ( $your_loop->have_posts() ) : $your_loop->the_post();
        $specialty = get_post_meta( get_the_ID(), '__specialty', true );

        if($specialty === $category) {
            return get_the_ID();
        } else {
            return FALSE;
        }
    endwhile; endif; wp_reset_postdata();
}

function getRecruiterByName($name) {
    $name = $name['firstName'] . ' ' . $name['lastName'];

    $recruiter = get_page_by_title($name, OBJECT, 'members');
    return $recruiter;
}
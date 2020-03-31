<?php
require_once(get_template_directory() . '/inc/ThemeCustomization.php');
require_once(get_template_directory() . '/inc/BullhornSettings.php');
require_once(get_template_directory() . '/inc/ThemeSettings.php');
require_once(get_template_directory() . '/inc/ContentSettings.php');
require_once(get_template_directory() . '/inc/BullhornAPI.php');

global $langs;
$langs['nl'] = json_decode(file_get_contents(get_template_directory_uri() . "/languages/nl.json"), true);
$langs['en'] = json_decode(file_get_contents(get_template_directory_uri() . "/languages/en.json"), true);

if ( ! function_exists( 'godo_setup' ) ) :
    function godo_setup() {
        load_theme_textdomain( 'godo', get_template_directory() . '/languages' );
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'post-thumbnails' );
        add_filter('show_admin_bar', '__return_false');
        remove_filter( 'the_content', 'wpautop' );

        register_nav_menus( array(
            'header-menu'   => __( 'Main Menu', 'godo' ),
            'header-menu-engels'   => __( 'Main Menu Engels', 'godo' ),
            'footer-menu'   => __( 'Footer Menu', 'godo' ),
            'footer-menu-engels'   => __( 'Footer Menu Engels', 'godo' )
        ) );

        add_theme_support( 'post-formats', array ( 'aside', 'gallery', 'quote', 'image', 'video' ) );
    }
endif;
add_action( 'after_setup_theme', 'godo_setup' );

add_filter('query_vars', function($vars) {
    $vars[] = "id";
    return $vars;
});

add_action( 'init', 'add_rules' );
function add_rules() {
    add_rewrite_rule('^vacature/([^/]*)/([^/]*)/?','index.php?page_id=29&id=$matches[2]','top');
    add_rewrite_rule('^vacature/([0-9]*)/?','index.php?page_id=29&id=$matches[1]','top');
}

function getLang() {
    global $langs;
    $lang = isset($_COOKIE["lang"]) ? $_COOKIE["lang"] : "nl";

    return $langs[$lang];
}

function saveLang() {
    global $langs;
    $data = $_POST;

    $file = get_template_directory() . "/languages/" . $data['lang'] . ".json";

    $json = $langs[$data['lang']];
    $json[$data['page']] = $data['data'];
    $success = file_put_contents($file, json_encode($json));

    if($success) {
        echo json_encode(array("success" => true));
    } else {
        echo json_encode(array("success" => false));
    }

    wp_die();
}
add_action( 'wp_ajax_saveLang', 'saveLang' );
add_action( 'wp_ajax_nopriv_saveLang', 'saveLang' );

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
                    'comments' => $post_data['comments'],
                );

                $candidate = $bullhorn->createCandidate($candidate_data, $candidate_files);
                if(FALSE === $candidate) : $failed = true; endif;
            else :
                $candidate = $duplicate_candidate['id'];
                $bullhorn->updateCandidate($candidate, $candidate_files);
            endif;
        endif;

        if(FALSE === $failed) :
            //Add candidate to Job Submission
            $submission = $bullhorn->addJobSubmission($candidate, $post_data['job'], $post_data['additions']);
            if(FALSE === $submission) : $failed = true; endif;
        endif;

        if(FALSE === $failed ) :
            echo json_encode(array('success' => true));
            $subject = "Nieuwe sollicitant op de vacature " . $post_data['jobTitle'];
            $to = $post_data['recruiterEmail'];

            $headers = "From: GoDo <wordpress@godo.nl>\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            $message = '<div style="background:#eeeeee;font-family:arial;color:#212121;text-align:center">';
            $message .= '<div style="width:560px;margin:32px auto 0 auto;background:#ffffff;box-sizing:border-box;padding:32px">';
            $message .= '<div style="padding:0 32px"><a href="https://godo.nl/" target="_blank"><img src="https://www.godo.nl/wp-content/themes/godo-1/assets/images/logo.svg" style="width: 50%" alt="GoDo"/></a></div>';
            $message .= '<div style="width:90%;height:2px;background:#eeeeee;margin:16px auto 16px auto"></div>';
            $message .= '<p><h3>'. $post_data['firstName'] .' ' . $post_data['lastName'] . ' heeft gereageerd op de vacature <a href="' . $post_data['url'] . '" target="_blank">' . $post_data['jobTitle'] . '</a></h3></p>';
            $message .= '<p style="margin-top:32px">';
            $message .= '<strong>Email: </strong>' . $post_data['email'];
            $message .= '<br /><strong>Telefoonnummer: </strong>' . $post_data['phone'];
            if($post_data['website']) { $message .= '<br /><strong>cv/portfolio/LinkedIn: </strong><a href="http://' . $post_data['website'] . '" target="_blank">' . $post_data['website'] . '</a>'; }
            if($post_data['additions']) { $message .= '<br /><strong>Motivatie of toelichting: </strong>' . $post_data['additions']; }
            $message .= '</p>';
            $message .= '<p style="margin-top:32px">' . $post_data['comments'] . '</p>';
            $message .= '</div>';

            $attachments = array();
            if($_FILES) {
                $uploads = wp_upload_dir();
                foreach($_FILES['file']['tmp_name'] as $key=>$resume) {
                    $name = basename($_FILES["file"]["name"][$key]);
                    move_uploaded_file($resume, $uploads['basedir'] . '/' . $name);
                    $attachments[] = $uploads['basedir'] . '/' . $name;
                }
            }

            if(wp_mail( $to, $subject, $message, $headers, $attachments )) {
                foreach($attachments as $attachment) {
                    unlink($attachment);
                }
            };

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

function addLead(){
    $bullhorn = new BullhornAPI();
    $post_data = $_POST;
    $failed = false;

    $description = "Skills: " . $post_data['skills'] . "\r\n Geïnteresseerd in de functies als " . $post_data['jobs'];

    $lead_data = array(
        'firstName' => $post_data['name'],
        'lastName' => $post_data['name'],
        'name' => $post_data['name'],
        'email' => $post_data['email'],
        'customTextBlock2' => $description,
        'status' => 'New Lead',
    );

    $lead = $bullhorn->createLead($lead_data);

    wp_die();
}
add_action( 'wp_ajax_addLead', 'addLead' );
add_action( 'wp_ajax_nopriv_addLead', 'addLead' );

function removeAlarm() {
    $allUsers = get_option('alarm_users');
    $email = $_GET['email'];
    $token = $_GET['token'];
    $success = false;

    if(isset($email) && isset($token)) {
        foreach ($allUsers as $key => $user) {
            if ($email === $user['email'] && $token === $user['token']) {
                array_splice($allUsers, $key, 1);
                $success = true;
            }
        }
    }

    $arr = array(
        "success" => $success
    );

    echo json_encode($arr);

    wp_die();
}
add_action( 'wp_ajax_removeAlarm', 'removeAlarm' );
add_action( 'wp_ajax_nopriv_removeAlarm', 'removeAlarm' );

function setAlarm(){
    $data = $_POST;
    $allUsers = get_option('alarm_users');

    $shouldUpdate = false;
    $curUser = null;

    foreach($allUsers as $key => $user) {
        if($data['email'] === $user['email']) {
            $shouldUpdate = true;
            $curUser = $key;
        }
    }

    if(!empty($allUsers) && $shouldUpdate) {
        $hasJob = false;
        $curJob = null;

        foreach($allUsers[$curUser]['jobs'] as $key => $job) {
            if($job['job'] === $data['job']) {
                $hasJob = true;
                $curJob = $key;
            }
        }

        if(!$hasJob) {
            $allUsers[$curUser]['jobs'][] = array("job" => $data['job'], "location" => array($data['location']));
        } else {
            if(!in_array($data['location'], $allUsers[$curUser]['jobs'][$curJob]['location'])) {
                $allUsers[$curUser]['jobs'][$curJob]['location'][] = $data['location'] ;
            }
        }
    } else {
        $arr = array();
        $arr['name'] = $data['name'];
        $arr['token'] = rtrim(base64_encode(md5(microtime())),"=");
        $arr['email'] = $data['email'];
        $arr['jobs'] = array(array("job" => $data['job'], "location" => array($data['location'])));

        $allUsers[] = $arr;
    }

    update_option( 'alarm_users', $allUsers);

    wp_die();
}
add_action( 'wp_ajax_setAlarm', 'setAlarm' );
add_action( 'wp_ajax_nopriv_setAlarm', 'setAlarm' );

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

function checkForNewJobs() {
    $bullhorn = new BullhornAPI();
    $jobs = $bullhorn->jobsFetchAll();
    $oldJobs = get_option('latest_jobs');
    $users = get_option('alarm_users');
    $newJobs = array();

    foreach ($jobs as $key => $job) {
        $newJobs[$key]['id'] = $job['id'];
        $newJobs[$key]['title'] = $job['title'];
        $newJobs[$key]['address']['address1'] = $job['address']['address1'];
        foreach ($job['categories']['data'] as $category) {
            $newJobs[$key]['categories'][] = $category['name'];
        }
    }

    $oldJobIDs = array();
    $newJobIds = array();

    foreach ($newJobs as $key => $job) {
        $newJobIds[] = $job['id'];
    }

    foreach ($oldJobs as $key => $job) {
        $oldJobIDs[] = $job['id'];
    }

    $diff = array_diff($newJobIds, $oldJobIDs);

    if(!empty($diff)) {
        $newestJobs = array();

        foreach ($diff as $dif) {
            foreach ($newJobs as $key => $job) {
                if($dif === $newJobs[$key]['id']) {
                    $newestJobs[] = $newJobs[$key];
                }
            }
        }

        foreach ($users as $user) {
            $interestingJobs =  array();

            foreach ($newestJobs as $job) {
                if ($job['id'] > end($oldJobs)['id'] ) {
                    foreach ($job['categories'] as $category) {
                        foreach ($user['jobs'] as $userJob) {
                            if (strpos(strtolower($category), strtolower($userJob['job'])) !== FALSE) {
                                if (count($userJob['location']) > 0) {
                                    $hasLocation = false;

                                    foreach ($userJob['location'] as $location) {
                                        if ($location !== "null") {
                                            $noArea = preg_replace("/\[(.*?)\]/", "", $location);

                                            if (strpos(strtolower($job['address']['address1']), strtolower($noArea)) !== FALSE) {
                                                $hasLocation = true;
                                            }
                                        } else {
                                            $interestingJobs[] = $job;
                                        }
                                    }

                                    if ($hasLocation) {
                                        $interestingJobs[] = $job;
                                    }
                                } else {
                                    $interestingJobs[] = $job;
                                }
                            }
                        }
                    }
                }
            }

            if(!empty($interestingJobs)) {
                $subject = "Nieuwe vacatures op de GoDo website";
                $to = $user['email'];

                $headers = "From: GoDo <menno@sandwichdigital.nl>\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                $message = '<div style="background:#eeeeee;font-family:arial;color:#212121;text-align:center">';
                $message .= '<div style="width:560px;margin:32px auto 0 auto;background:#ffffff;box-sizing:border-box;padding:32px">';
                $message .= '<div style="padding:0 32px"><a href="https://godo.nl/" target="_blank"><img src="https://www.godo.nl/wp-content/uploads/2020/03/Logo-GoDo-01.png" style="width: 50%" alt="GoDo"/></a></div>';
                $message .= '<div style="width:90%;height:2px;background:#eeeeee;margin:16px auto 16px auto"></div>';
                $message .= '<p><h3>Er zijn nieuwe vacatures op de GoDo website geplaatst die mogelijk interessant voor jou kunnen zijn!</h3></p>';
                $message .= '<p style="margin-top:32px">';
                $message .= '<table style="margin: 0 auto;">';
                foreach($interestingJobs as $job) {
                    $message .= '<tr>';
                    $message .= '<td><a href="' . get_site_url() . '/vacature/'. $job['id'] .'" target="_blank">' . $job['title'] . ' in '. $job['address']['address1'] .'</a></td>';
                    $message .= '</tr>';
                }
                $message .= '</table>';
                $message .= '</p>';
                $message .= '<p>Wil je geen alerts meer ontvangen? <a href="">Uitschrijven</a></p>';
                $message .= '</div>';

                wp_mail( $to, $subject, $message, $headers );
            }
        }
    }

    update_option( 'latest_jobs', $jobs);
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
    wp_enqueue_script( 'js-cookie', 'https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js',array(), $theme_version, false );
    wp_enqueue_script( 'popupJS', get_template_directory_uri() . '/assets/js/popup.js', $theme_version, false );
    wp_enqueue_script( 'helperJS', get_template_directory_uri() . '/assets/js/helper.js', $theme_version, false );

    $translation_array = array( 'templateUrl' => get_template_directory_uri() );
    $admin_array = array( 'ajax' => admin_url( 'admin-ajax.php' ) );

    $theme_settings = get_option( 'theme_settings' );
    $agileJobs = preg_split("/\r\n|\n|\r/", $theme_settings['helperAgile']);
    $designJobs = preg_split("/\r\n|\n|\r/", $theme_settings['helperDesign']);
    $devJobs = preg_split("/\r\n|\n|\r/", $theme_settings['helperDev']);
    $infraJobs = preg_split("/\r\n|\n|\r/", $theme_settings['helperInfra']);
    $job_array = array(
        "agile" => $agileJobs,
        "design" => $designJobs,
        "development" => $devJobs,
        "infrastructuur" => $infraJobs
    );

    wp_localize_script('helperJS', 'adminURLs', $admin_array );
    wp_localize_script('helperJS', 'wpDirectory', $translation_array );
    wp_localize_script('helperJS', 'helperJobs', $job_array );
}
add_action( 'wp_enqueue_scripts', 'register_scripts' );

function misha_include_myuploadscript() {
    /*
     * I recommend to add additional conditions just to not to load the scipts on each page
     * like:
     * if ( !in_array('post-new.php','post.php') ) return;
     */
    if ( ! did_action( 'wp_enqueue_media' ) ) {
        wp_enqueue_media();
    }

    wp_enqueue_script( 'myuploadscript', get_template_directory_uri() . '/assets/js/uploadscript.js', array('jquery'), null, false );
}

add_action( 'admin_enqueue_scripts', 'misha_include_myuploadscript' );

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

    $slider_labels = array(
        'name'                => _x( 'Slider', 'Post Type General Name', 'text_domain' ),
        'singular_name'       => _x( 'Slider', 'Post Type Singular Name', 'text_domain' ),
        'all_items'           => __( 'Alle slides', 'text_domain' ),
        'view_item'           => __( 'Bekijk slides', 'text_domain' ),
        'add_new'             => __( 'Slide toevoegen', 'text_domain' ),
        'add_new_item'        => __( 'Nieuwe slide toevoegen', 'text_domain' ),
        'edit_item'           => __( 'Slide wijzigen', 'text_domain' ),
        'update_item'         => __( 'Slide updaten', 'text_domain' ),
        'search_items'        => __( 'Slide zoeken', 'text_domain' ),
    );

    $slider_args = array(
        'labels' => $slider_labels,
        'public' => true,
        'has_archive' => false,
        'rewrite' => array('slug' => 'slider'),
        'menu_icon' => 'dashicons-images-alt2',
        'supports' => array( 'title', 'thumbnail' ),
    );
    register_post_type( 'slider', $slider_args );
}
add_action( 'init', 'custom_post_types' );

function misha_image_uploader_field( $name, $value = '') {
    $image = 'Uploaden';
    $image_size = 'full'; // it would be better to use thumbnail size here (150x150 or so)
    $display = 'none'; // display state ot the "Remove image" button

    if( $image_attributes = wp_get_attachment_image_src( $value, $image_size ) ) {

        // $image_attributes[0] - image URL
        // $image_attributes[1] - image width
        // $image_attributes[2] - image height

        $image = '<img src="' . $image_attributes[0] . '" style="width:50%;display:block;" />';
        $display = 'inline-block';

    }

    return '<div><a href="#" class="misha_upload_image_button button">'. $image .'</a><input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" /><a href="#" class="misha_remove_image_button" style="display:inline-block;display:' . $display . '">Remove image</a></div>';
}

function add_metaboxes() {
    add_meta_box(
        'team_details',           // Unique ID
        'Details',  // Box title
        'build_member_meta_box',  // Content callback, must be of type callable
        'members',
        'normal', // $context
        'high' // $priority
    );

    add_meta_box(
        'images',           // Unique ID
        'Afbeeldingen',  // Box title
        'build_images_meta_box',  // Content callback, must be of type callable
        'page',
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

function build_images_meta_box( $post ){
    // make sure the form request comes from WordPress
    wp_nonce_field( basename( __FILE__ ), 'images_meta_box_nonce' );

    if ( 'templates/template-frontpage.php' == get_post_meta( $post->ID, '_wp_page_template', true ) ) {
        $cur_company = get_post_meta($post->ID, 'company', true); ?>

        <p>
            <label for="company">Voor bedrijven</label>
            <?php echo misha_image_uploader_field( 'company', $cur_company ); ?>
        </p>
    <?php }

    if ( 'templates/template-about.php' == get_post_meta( $post->ID, '_wp_page_template', true ) ) {
        $cur_team = get_post_meta($post->ID, 'team', true);
        $cur_makerstreet = get_post_meta($post->ID, 'makerstreet', true); ?>

        <p>
            <label for="company">Teamfoto</label>
            <?php echo misha_image_uploader_field( 'team', $cur_team ); ?>
        </p>

        <p>
            <label for="makerstreet">Makerstreet logo</label>
            <?php echo misha_image_uploader_field( 'makerstreet', $cur_makerstreet ); ?>
        </p>
    <?php }

    if ( 'templates/template-single_job.php' == get_post_meta( $post->ID, '_wp_page_template', true ) ) {
        $design = get_post_meta($post->ID, 'design', true);
        $designMobile = get_post_meta($post->ID, 'designMobile', true);
        $dev = get_post_meta($post->ID, 'dev', true);
        $devMobile = get_post_meta($post->ID, 'devMobile', true);
        $infra = get_post_meta($post->ID, 'infra', true);
        $infraMobile = get_post_meta($post->ID, 'infraMobile', true);
        $agile = get_post_meta($post->ID, 'agile', true);
        $agileMobile = get_post_meta($post->ID, 'agileMobile', true);
        $misc = get_post_meta($post->ID, 'misc', true);
        $miscMobile = get_post_meta($post->ID, 'miscMobile', true);

        ?>

        <p>
            <h3>Design</h3>
            <label for="design">Desktop</label>
            <?php echo misha_image_uploader_field( 'design', $design ); ?>

            <label for="designMobile">Mobile</label>
            <?php echo misha_image_uploader_field( 'designMobile', $designMobile ); ?>
        </p>

        <p>
            <h3>Dev</h3>
            <label for="design">Desktop</label>
            <?php echo misha_image_uploader_field( 'dev', $dev ); ?>

            <label for="designMobile">Mobile</label>
            <?php echo misha_image_uploader_field( 'devMobile', $devMobile ); ?>
        </p>

        <p>
            <h3>Infra</h3>
            <label for="design">Desktop</label>
            <?php echo misha_image_uploader_field( 'infra', $infra ); ?>

            <label for="designMobile">Mobile</label>
            <?php echo misha_image_uploader_field( 'infraMobile', $infraMobile ); ?>
        </p>

        <p>
            <h3>Agile</h3>
            <label for="design">Desktop</label>
            <?php echo misha_image_uploader_field( 'agile', $agile ); ?>

            <label for="designMobile">Mobile</label>
            <?php echo misha_image_uploader_field( 'agileMobile', $agileMobile ); ?>
        </p>

        <p>
            <h3>Misc</h3>
            <label for="design">Desktop</label>
            <?php echo misha_image_uploader_field( 'misc', $misc ); ?>

            <label for="designMobile">Mobile</label>
            <?php echo misha_image_uploader_field( 'miscMobile', $miscMobile ); ?>
        </p>
    <?php }
}

function save_images_meta_box( $post_id ){
    // verify taxonomies meta box nonce
    if ( !isset( $_POST['images_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['images_meta_box_nonce'], basename( __FILE__ ) ) ){
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
    if ( isset( $_REQUEST['company'] ) ) {
        update_post_meta($post_id, 'company', sanitize_text_field($_POST['company']));
    }

    if ( isset( $_REQUEST['team'] ) ) {
        update_post_meta($post_id, 'team', sanitize_text_field($_POST['team']));
    }

    if ( isset( $_REQUEST['makerstreet'] ) ) {
        update_post_meta($post_id, 'makerstreet', sanitize_text_field($_POST['makerstreet']));
    }



    if ( isset( $_REQUEST['design'] ) ) {
        update_post_meta($post_id, 'design', sanitize_text_field($_POST['design']));
    }

    if ( isset( $_REQUEST['designMobile'] ) ) {
        update_post_meta($post_id, 'designMobile', sanitize_text_field($_POST['designMobile']));
    }

    if ( isset( $_REQUEST['dev'] ) ) {
        update_post_meta($post_id, 'dev', sanitize_text_field($_POST['dev']));
    }

    if ( isset( $_REQUEST['devMobile'] ) ) {
        update_post_meta($post_id, 'devMobile', sanitize_text_field($_POST['devMobile']));
    }

    if ( isset( $_REQUEST['infra'] ) ) {
        update_post_meta($post_id, 'infra', sanitize_text_field($_POST['infra']));
    }

    if ( isset( $_REQUEST['infraMobile'] ) ) {
        update_post_meta($post_id, 'infraMobile', sanitize_text_field($_POST['infraMobile']));
    }

    if ( isset( $_REQUEST['agile'] ) ) {
        update_post_meta($post_id, 'agile', sanitize_text_field($_POST['agile']));
    }

    if ( isset( $_REQUEST['agileMobile'] ) ) {
        update_post_meta($post_id, 'agileMobile', sanitize_text_field($_POST['agileMobile']));
    }

    if ( isset( $_REQUEST['misc'] ) ) {
        update_post_meta($post_id, 'misc', sanitize_text_field($_POST['misc']));
    }

    if ( isset( $_REQUEST['miscMobile'] ) ) {
        update_post_meta($post_id, 'miscMobile', sanitize_text_field($_POST['miscMobile']));
    }
}
add_action( 'save_post', 'save_images_meta_box' );

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
        } else if(stripos($catName, "scrum") !== FALSE || stripos($catName, "owner") !== FALSE) {
            $category = "agile";
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
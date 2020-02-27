<?php
function theme_options_add(){
    register_setting( 'theme_settings', 'theme_settings' );
}

function add_theme_options() {
    add_menu_page( __( 'Theme settings' ), __( 'Theme settings' ), 'manage_options', 'theme_settings', 'theme_options_page');
}
add_action( 'admin_init', 'theme_options_add' );
add_action( 'admin_menu', 'add_theme_options' );

function theme_options_page() {
    if ( ! isset( $_REQUEST['updated'] ) )
        $_REQUEST['updated'] = false;
    ?>
    <div class="wrap">
        <form method="post" action="options.php">
            <h1>Filters</h1>
            <?php settings_fields( 'theme_settings' ); ?>
            <?php $filters = get_option( 'theme_settings' ); ?>
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row"><?php _e( 'Functie titels (seperated by enter)' ); ?></th>
                    <td><textarea id="theme_settings[filters]" type="text" cols="50" rows="10" name="theme_settings[filters]" ><?php esc_attr_e( $filters['filters'] ); ?></textarea></td>
                </tr>
                </tbody>
            </table>
            <p class="submit"><input name="submit" id="submit" class="button button-primary" value="Save Changes" type="submit"></p>
        </form>

    </div>

    <script type="text/javascript">
        function addFilterField() {

        }
    </script>
    <?php
}
?>
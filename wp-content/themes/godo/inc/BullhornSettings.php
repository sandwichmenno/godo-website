<?php
require_once(get_template_directory() . '/inc/BullhornAPI.php');

function bullhorn_options_add(){
    register_setting( 'bullhorn_settings', 'bullhorn_settings' );
}

function add_options() {
    add_menu_page( __( 'Bullhorn settings' ), __( 'Bullhorn settings' ), 'manage_options', 'settings', 'bullhorn_options_page');
}
add_action( 'admin_init', 'bullhorn_options_add' );
add_action( 'admin_menu', 'add_options' );

function bullhorn_options_page() {
    $bullhorn_accessToken = get_option('bullhorn_access_token', null);
    $bullhorn_authToken = get_option('bullhorn_auth_token', null);
    $bullhorn_refreshToken = get_option('bullhorn_refresh_token', null);
    $bullhorn_restToken = get_option('bullhorn_rest_token', null);
    $isAuthenticated = '';

    if(!empty($bullhorn_accessToken) && !empty($bullhorn_authToken) && !empty($bullhorn_refreshToken) && !empty($bullhorn_restToken)) {
        $isAuthenticated = '';
    } else {
        $isAuthenticated = 'not';
    }

    if ( ! isset( $_REQUEST['updated'] ) )
        $_REQUEST['updated'] = false;
    ?>
    <div class="wrap">
        <form method="post" action="options.php">
            <h1>Bullhorn API settings</h1>
            <?php settings_fields( 'bullhorn_settings' ); ?>
            <?php $options = get_option( 'bullhorn_settings' ); ?>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><?php _e( 'Client ID' ); ?></th>
                        <td><input id="bullhorn_settings[client_id]" type="text" size="36" name="bullhorn_settings[client_id]" value="<?php esc_attr_e( $options['client_id'] ); ?>" class="regular-text" /></td>
                    </tr>

                    <tr>
                        <th scope="row"><?php _e( 'Client secret' ); ?></th>
                        <td><input id="bullhorn_settings[client_secret]" type="text" size="36" name="bullhorn_settings[client_secret]" value="<?php esc_attr_e( $options['client_secret'] ); ?>" class="regular-text" /></td>
                    </tr>

                    <tr>
                        <th scope="row"><?php _e( 'Username' ); ?></th>
                        <td><input id="bullhorn_settings[username]" type="text" size="36" name="bullhorn_settings[username]" value="<?php esc_attr_e( $options['username'] ); ?>" class="regular-text" /></td>
                    </tr>

                    <tr>
                        <th scope="row"><?php _e( 'Password' ); ?></th>
                        <td><input id="bullhorn_settings[password]" type="text" size="36" name="bullhorn_settings[password]" value="<?php esc_attr_e( $options['password'] ); ?>" class="regular-text" /></td>
                    </tr>
                </tbody>
            </table>
            <p class="submit"><input name="submit" id="submit" class="button button-primary" value="Save Changes" type="submit"></p>
        </form>

    </div>

    <?php
}
?>
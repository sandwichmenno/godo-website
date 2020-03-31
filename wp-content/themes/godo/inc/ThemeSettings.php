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
                <tr><td><h3>Filters</h3></td></tr>
                <tr>
                    <th scope="row"><?php _e( 'Functies (seperated by enter)' ); ?></th>
                    <td><textarea id="theme_settings[filtersJob]" type="text" cols="50" rows="10" name="theme_settings[filtersJob]" ><?php esc_attr_e( $filters['filtersJob'] ); ?></textarea></td>
                </tr>

                <tr>
                    <th scope="row"><?php _e( 'Locaties (seperated by enter)' ); ?></th>
                    <td><textarea id="theme_settings[filtersLocation]" type="text" cols="50" rows="10" name="theme_settings[filtersLocation]" ><?php esc_attr_e( $filters['filtersLocation'] ); ?></textarea></td>
                </tr>

                <tr><td><h3>Helper functies</h3></td></tr>
                <tr>
                    <th scope="row">Agile (seperated by enter)</th>
                    <td><textarea id="theme_settings[helperAgile]" type="text" cols="50" rows="10" name="theme_settings[helperAgile]" ><?php esc_attr_e( $filters['helperAgile'] ); ?></textarea></td>
                </tr>

                <tr>
                    <th scope="row">Design (seperated by enter)</th>
                    <td><textarea id="theme_settings[helperDesign]" type="text" cols="50" rows="10" name="theme_settings[helperDesign]" ><?php esc_attr_e( $filters['helperDesign'] ); ?></textarea></td>
                </tr>

                <tr>
                    <th scope="row">Development (seperated by enter)</th>
                    <td><textarea id="theme_settings[helperDev]" type="text" cols="50" rows="10" name="theme_settings[helperDev]" ><?php esc_attr_e( $filters['helperDev'] ); ?></textarea></td>
                </tr>

                <tr>
                    <th scope="row">Infrastructuur (seperated by enter)</th>
                    <td><textarea id="theme_settings[helperInfra]" type="text" cols="50" rows="10" name="theme_settings[helperInfra]" ><?php esc_attr_e( $filters['helperInfra'] ); ?></textarea></td>
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
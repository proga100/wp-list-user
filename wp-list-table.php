<?php
/*
 * Plugin Name: Talabalar tolovlar royhati
 * Description:  Talabalar tolovlar royhat
 * Plugin URI: flance.info
 * Author: rusty
 * Author URI: flance.info
 * Version: 1.0
 * License: GPL2
 */

if( !defined( 'ABSPATH' ) ) exit; //Exit if accessed directly

define( 'STM_WP_PAYMENT_FILE', __FILE__ );
define( 'STM_WP_PAYMENT_PATH', dirname( STM_WP_PAYMENT_FILE ) );
define( 'STM_WP_PAYMENT_URL', plugin_dir_url( STM_WP_PAYMENT_FILE ) );
define( 'STM_WP_PAYMENT_VERSION', '1' );
define( 'STM_WP_PAYMENT_DB_VERSION', '1' );


if( !is_textdomain_loaded( 'wp-list-user' ) ) {
    load_plugin_textdomain(
        'wp-list-user',
        false,
        'wp-list-user/languages'
    );
}

require_once 'user-list-table.php';
if (is_admin()) {
    new Paulund_Wp_List_Table();
     require_once 'user-reports.php';
    require_once 'user-form.php';

}

/**
 * Paulund_Wp_List_Table class will create the page to load the table
 */
class Paulund_Wp_List_Table
{
    /**
     * Constructor will create the menu item
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_menu_example_list_table_page'));
    }

    /**
     * Menu item will allow us to load the page to display the table
     */
    public function add_menu_example_list_table_page()
    {
        add_menu_page('Talabalar to\'lovlar royhati', 'Talabalar to\'lovlar royhati', 'manage_options', 'user-list-table', array($this, 'list_table_page'));
    }

    /**
     * Display the list table page
     *
     * @return Void
     */
    public function list_table_page()
    {
        $exampleListTable = new Users_List_Table();
        $exampleListTable->prepare_items();
        ?>
        <div class="wrap">
            <div id="icon-users" class="icon32"></div>
            <h2>Talabalar to'lovlar royhati</h2>
            <?php $exampleListTable->display(); ?>
        </div>
        <?php
    }
}


?>
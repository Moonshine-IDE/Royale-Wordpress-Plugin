<?php

/**
 * Provide a view in the wp-admin menu, for the list of shorecodes registered with this plugin
 *
 *
 * @link       https://clearmedia.pl/
 * @since      1.0.0
 *
 * @package    Royal_Wordpress_Plugin
 * @subpackage Royal_Wordpress_Plugin/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
?>

<div class="wrap" id="rwp-shortcodes-list-table">
    <h1 class="wp-heading-inline">List of shortcodes</h1>
    <hr class="wp-header-end">
    <form method="post">
        <?php
            $shortcodes_list->prepare_items();
            $shortcodes_list->display();
        ?>
    </form>
</div>
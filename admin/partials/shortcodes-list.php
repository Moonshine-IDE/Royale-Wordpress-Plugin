<?php

/**
 * Provide a view in the wp-admin menu, for the list of shorecodes registered with this plugin
 *
 *
 * @link       https://clearmedia.pl/
 * @since      1.0.0
 *
 * @package    Royale_Wordpress_Plugin
 * @subpackage Royale_Wordpress_Plugin/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
?>

<div class="wrap" id="rwp-shortcodes-list-table">
    <h1 class="wp-heading-inline"><?= __( 'List of all scripts', 'royale-wordpress-plugin' ) ?></h1>
    <a href="<?= admin_url('admin.php?page=upload_a_new_script') ?>" class="page-title-action"><?= __( 'Add New', 'royale-wordpress-plugin' ) ?></a>
    <hr class="wp-header-end">
    <form method="post">
        <?php
            $shortcodes_list->display();
        ?>
    </form>
</div>
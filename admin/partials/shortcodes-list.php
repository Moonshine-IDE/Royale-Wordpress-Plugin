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
?>

<div class="wrap" id="rwp-shortcodes-list-table">
    <h1 class="wp-heading-inline">RWP list of shortcodes</h1>
    <?php if ( is_array($shortcode_names) && !empty($shortcode_names) ) : ?>
        <ul>
            <?php foreach ($shortcode_names as $shortcode_name) : ?>
                <li>[royal_wp_plugin name="<?= $shortcode_name ?>"]</li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
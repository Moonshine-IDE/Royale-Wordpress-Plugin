<?php

/**
 * Provide a view for the shortcode registered with this plugin
 *
 *
 * @link       https://clearmedia.pl/
 * @since      1.0.0
 *
 * @package    Royal_Wordpress_Plugin
 * @subpackage Royal_Wordpress_Plugin/public/partials
 */
?>

<?php if ( !empty($rwp_plugin_shortcode_url) ) : ?>
    <iframe class="rwp-iframe hidden" src="<?= $rwp_plugin_shortcode_url ?>"></iframe>
<?php endif; ?>
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
    <iframe src="<?= $rwp_plugin_shortcode_url ?>" style="width: 100%; height: 100%; background: transparent; border: none;"></iframe>
<?php endif; ?>
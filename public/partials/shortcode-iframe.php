<?php

/**
 * Provide a view for the shortcode registered with this plugin
 *
 *
 * @link       https://clearmedia.pl/
 * @since      1.0.0
 *
 * @package    Royale_Wordpress_Plugin
 * @subpackage Royale_Wordpress_Plugin/public/partials
 */
?>

<?php if ( !empty( $shortcode_url ) ) : ?>
    <iframe class="rwp-iframe hidden" src="<?= $shortcode_url ?>"></iframe>
<?php endif; ?>
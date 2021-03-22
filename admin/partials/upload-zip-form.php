<?php

/**
 * Provide a view in Wordpress'es admin menu, for the form for uploading zip files
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

<div class="wrap" id="rwp-upload-zip-form" class="rwp-upload-zip-form">
  <h1 class="wp-heading-inline"><?= $page_title ?? __( 'Upload a new script', 'royal-wordpress-plugin' ) ?></h1>
  <?php if ( $page_subtitle ) : ?>
    <p class="wp-heading-inline shortcode"><?= $page_subtitle ?></p>
  <?php endif; ?>
  <?php if ( $notices ) : ?>
    <ul>
      <?php foreach( $notices as $notice ) : ?>
        <li><?= $notice ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
  <?php if ( $errors_messages ) : ?>
    <ul>
      <?php foreach( $errors_messages as $message ) : ?>
        <li><?= $message; ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
  <form method="POST" action="" enctype="multipart/form-data">
    <?php
    // wp_nonce_field( 'zip_upload_nonce', 'zip_upload_nonce' );
    wp_nonce_field( $rwp_wp_nonce_action );
    ?>
    <table class="form-table" role="presentation">
	    <tbody>
        <tr class="form-field form-required">
		      <th scope="row">
            <label for="shortcode-name"><?= __( 'Shortcode name', 'royal-wordpress-plugin' ) ?> <span class="description">(<?= __( 'required', 'royal-wordpress-plugin' ) ?>)</span></label>
          </th>
		      <td>
            <input name="shortcode-name" type="text" id="shortcode-name" value="<?= $shortcode_name ?? '' ?>" autocapitalize="none" autocorrect="off" maxlength="60" required>
          </td>
	      </tr>
        <tr class="form-field">
          <th scope="row">
            <label for="shortcode-description"><?= __( 'Shortcode description', 'royal-wordpress-plugin' ) ?> </label>
          </th>
          <td>
            <textarea name="shortcode-description" id="shortcode-description" maxlength="255" rows="5"><?= $shortcode_description ?? '' ?></textarea>
          </td>
	      </tr>
        <tr class="form-field form-required">
		      <th scope="row">
            <label for="file">
              <?= __( 'Zip file', 'royal-wordpress-plugin' ) ?>
              <?php if( $is_file_upload_required ) : ?>
                <span class="description">(<?= __( 'required', 'royal-wordpress-plugin' ) ?>)</span>
              <?php endif; ?>
            </label>
          </th>
		      <td>
            <input type="file" accept="application/zip" name="file" id="file"<?= $is_file_upload_required ? ' required' : '' ?>/>
          </td>
	      </tr>
		  </tbody>
    </table>
    <p class="submit">
      <input type="submit" name="upload_file" id="upload_file" class="button button-primary" value="<?= $submit_button_text ?? __( 'Upload', 'royal-wordpress-plugin' ) ?>">
    </p>
  </form>
</div>
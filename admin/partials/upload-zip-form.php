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
  <h1 class="wp-heading-inline"><?= __( 'Upload a new shortcode', 'royal-wordpress-plugin' ) ?></h1>
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
    wp_nonce_field( 'zip_upload_nonce', 'zip_upload_nonce' );
    ?>
    <table class="form-table" role="presentation">
	    <tbody>
        <tr class="form-field form-required">
		      <th scope="row">
            <label for="shortcode-name"><?= __( 'Shortcode name', 'royal-wordpress-plugin' ) ?> <span class="description">(<?= __( 'required', 'royal-wordpress-plugin' ) ?>)</span></label>
          </th>
		      <td>
            <input name="shortcode-name" type="text" id="shortcode-name" value="" autocapitalize="none" autocorrect="off" maxlength="60" required>
          </td>
	      </tr>
        <tr class="form-field">
          <th scope="row">
            <label for="shortcode-description"><?= __( 'Shortcode description', 'royal-wordpress-plugin' ) ?> </label>
          </th>
          <td>
            <textarea name="shortcode-description" id="shortcode-description" value="" maxlength="255" rows="5"></textarea>
          </td>
	      </tr>
        <tr class="form-field form-required">
		      <th scope="row">
            <label for="file"><?= __( 'Zip file', 'royal-wordpress-plugin' ) ?> <span class="description">(<?= __( 'required', 'royal-wordpress-plugin' ) ?>)</span></label>
          </th>
		      <td>
            <input type="file" accept="application/zip" name="file" id="file" required/>
          </td>
	      </tr>
		  </tbody>
    </table>
    <p class="submit">
      <input type="submit" name="upload_file" id="upload_file" class="button button-primary" value="Upload">
    </p>
  </form>
</div>
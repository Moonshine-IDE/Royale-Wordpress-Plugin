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
  <h1 class="wp-heading-inline">Upload a new shortcode</h1>
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
    <div class="wrap wrap--input">
      <label for="shortcode-name">Shortcode name</label>
      <input type="text" name="shortcode-name" id="shortcode-name"/>
    </div>
    <div class="wrap wrap--input">
      <label for="shortcode-description">Shortcode description</label>
      <input type="text" name="shortcode-description" id="shortcode-description"/>
    </div>
    <div class="wrap wrap--input">
      <input type="file" accept="application/zip" name="file"/>
      <button class="submit button" name="upload_file" type="submit">Upload</button>
    </div>
  </form>
</div>
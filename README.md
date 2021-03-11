# Royale-Wordpress-Plugin

Stable tag: 1.0.0  
Requires at least: Wordpress 5.0  
Tested up to: Wordpress 5.7.2  
Requires PHP: 7.0  
License: GPL v2 or later  
Tags: custom scripts, custom shortcodes  
Contributors: clearmedia  

Include Royale pages in a Wordpress Website.

## Description

This plugin allows you to add your custom scripts to your wordpress site. It enables you to upload scripts files to the server. Then you can use these scripts as shortcodes in your posts' and pages' content.

## How to install

 * Download code from GitHub as .zip file.
 * Go to your Wordpress site dashboard and upload the .zip file through the "Plugins" menu.
 * Instead of this, you can unzip the plugin into the "/wp-content/plugins/" directory.

## How to use

### Requirements for your custom script's files

 * Your custom script needs to consist of at least index.html file.
 * You can add also assets (.js, .css files, images) which would be used in the index.html file. You should link them with relative paths.
 * All of you script's files should be compressed together into a single .zip file.
 * The scipt's files can be wrapped into a directory before compressing into the .zip file.

### Uploading and managing your custom scripts

 1. Visit the *Royal Wordpress Plugin* menu in WordPress.
 2. You can use *Upload a new spript* submenu to upload a .zip file with your custom script.
 3. In the *List of scripts* submenu you can see all the custom scripts that you have already uploaded. You can also see shortcodes with which you can insert you scripts into your posts' and pages' content.
 4. You can also delete the scripts in this submenu.

### Using your custom scripts in your posts' and pages' content

 Copy the chosen shortcode from the *List of scripts* submenu.
 Insert it into the content of you post/page.
 The content of you script will be rendered on you post/page wrapped into an iframe.
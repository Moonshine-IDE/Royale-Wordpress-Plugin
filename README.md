# Royale-Wordpress-Plugin

Stable tag: 1.0.0  
Requires at least: Wordpress 5.0  
Tested up to: Wordpress 5.7.2  
Requires PHP: 7.0  
License: GPL v2 or later  
Tags: custom scripts, custom shortcodes  
Contributors: clearmedia  

Include Apache Royale® pages in a Wordpress Website.

## Description

This plugin allows you to add custom scripts in iframes in your Wordpress site.  You can upload scripts to the server through th plugin interface.  Then you can add these scripts to your posts and pages using shortcodes.

## How to Install

 * Download the code from GitHub as .zip file.
 * Go to your Wordpress site dashboard and upload the .zip file through the "Plugins" menu.
 * Alternatively, you can unzip the plugin into the "/wp-content/plugins/" directory.

## How to Use

### Requirements for Your Custom Script

 * All of your script's files should be compressed together into a single .zip file.
 * The script's files can be wrapped into a directory before compressing into the .zip file.
 * Your custom script must have an index.html file in base directory or first nested directory.
 * You can add also assets (.js, .css files, images) which would be used in the index.html file. You should link them with relative paths.

### Uploading and Managing Your Custom Scripts

 1. Visit the *Apache Royale® Apps* menu in WordPress.
 2. You can use the *Upload a new script* submenu to upload a .zip file with your custom script.
 3. In the *List of scripts* submenu you can see all the custom scripts that you have already uploaded. You can also see shortcodes which you can insert into your posts and pages.
 4. You can also delete the scripts in the *List of scripts* submenu.

### Using Your Custom Scripts in Your Posts and Pages

 Copy the shortcode for the desired script from the *List of scripts* submenu.
 Insert it into the content of your post or page.
 Your script will be rendered in your post/page, wrapped in an iframe.

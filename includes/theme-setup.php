<?php
/**
 * DesignsLabz Theme Setup and Theme Options
 *
 * @package designslabz
 */

/**
 * Setup theme support and menus
 */
function dl_theme_setup() {
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('html5', ['search-form', 'gallery', 'caption']);
	add_theme_support('custom-logo');
	add_theme_support('editor-styles');
	add_theme_support('wp-block-styles');
	add_theme_support('responsive-embeds');

	register_nav_menus([
		'primary' => __('Header Menu', 'designslabz'),
		'footer'  => __('Footer Menu', 'designslabz'),
	]);
}
add_action('after_setup_theme', 'dl_theme_setup');

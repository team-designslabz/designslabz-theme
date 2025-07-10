<?php
/**
 * DesignsLabz Theme Setup
 *
 * @package designslabz
 */

if (!class_exists('DL_Theme')) {

	class DL_Theme {

		/**
		 * Constructor: Hooks everything up.
		 */
		public function __construct() {
			add_action('after_setup_theme', [$this, 'setup_theme']);
			add_action('wp_head', [$this, 'add_meta_tags'], 0);
			add_filter('body_class', [$this, 'add_theme_name_body_class'], PHP_INT_MAX);
		}

		/**
		 * Theme setup: supports (menus removed).
		 */
		public function setup_theme() {
			add_theme_support('title-tag');
			add_theme_support('post-thumbnails');
			add_theme_support('html5', ['search-form', 'gallery', 'caption']);
			add_theme_support('custom-logo');
			add_theme_support('editor-styles');
			add_theme_support('wp-block-styles');
			add_theme_support('responsive-embeds');
		}

		/**
		 * Output meta tags.
		 */
		public function add_meta_tags() {
			echo '<meta charset="' . esc_attr(get_bloginfo('charset')) . '">' . "\n";
			echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
		}

		/**
		 * Add theme directory name as body class.
		 *
		 * @param array $classes
		 * @return array
		 */
		public function add_theme_name_body_class($classes) {
			$theme_path = get_template_directory();
			$theme_bits = explode('/', $theme_path);
			$theme_name = array_pop($theme_bits);

			if (!empty($theme_name) && !is_numeric($theme_name)) {
				array_unshift($classes, sanitize_html_class($theme_name));
			}

			return $classes;
		}

	}

	// Initialize the class
	new DL_Theme();
}

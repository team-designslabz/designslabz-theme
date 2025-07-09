<?php
/**
 * DesignsLabz Theme Options Class
 *
 * @package designslabz
 */

if ( ! class_exists( 'DL_Theme_Options' ) ) {

	class DL_Theme_Options {

		/**
		 * Constructor: Hooks into admin actions and AJAX.
		 */
		public function __construct() {
			add_action( 'admin_menu', [ $this, 'add_theme_options_page' ] );
			add_action( 'admin_init', [ $this, 'register_settings' ] );
			add_action( 'wp_ajax_dl_get_block_preview', [ $this, 'get_block_preview_ajax' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
		}

		/**
		 * Add Theme Options page to admin menu.
		 */
		public function add_theme_options_page() {
			add_menu_page(
				__( 'DesignsLabz Theme Options', 'designslabz' ),
				__( 'Theme Options', 'designslabz' ),
				'edit_theme_options',
				'dl-theme-options',
				[ $this, 'render_options_page' ],
				'dashicons-admin-customizer',
				60
			);
		}

		/**
		 * Enqueue admin styles and scripts.
		 */
		public function enqueue_admin_scripts( $hook ) {
			if ( $hook !== 'toplevel_page_dl-theme-options' ) {
				return;
			}

			// Load core block styles
			wp_enqueue_style('wp-block-library');
			wp_enqueue_style('wp-block-library-theme');

			// Load your theme's frontend styles
			wp_enqueue_style(
				'dl-frontend-style',
				get_stylesheet_uri(),
				[],
				filemtime(get_stylesheet_directory() . '/style.css')
			);

			// Load admin styles
			if ($admin_style = $this->get_asset_url('admin-style.css')) {
				wp_enqueue_style('dl-admin-style', $admin_style, [], null);
			}

			// Load admin scripts
			if ($admin_js = $this->get_asset_url('admin-scripts.js')) {
				wp_enqueue_script('dl-admin-script', $admin_js, ['jquery'], null, true);
				wp_localize_script(
					'dl-admin-script',
					'dlAdminConfig',
					[
						'ajaxurl' => admin_url('admin-ajax.php'),
						'blockPreviewNonce' => wp_create_nonce('dl_block_preview'),
						'themeOptionsPage' => admin_url('admin.php?page=dl-theme-options')
					]
				);
			}
		}

		/**
		 * Get built asset URL from manifest.json.
		 */
		public function get_asset_url( $filename ) {
			$manifest_path = get_template_directory() . '/assets/build/manifest.json';
			if ( ! file_exists( $manifest_path ) ) return '';
			$manifest = json_decode( file_get_contents( $manifest_path ), true );
			if ( isset( $manifest[ $filename ] ) ) {
				return get_template_directory_uri() . '/assets/build/' . $manifest[ $filename ];
			}
			return '';
		}

		/**
		 * Render the Theme Options page markup.
		 */
		public function render_options_page() {
			$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'header';
			?>
			<div class="wrap">
				<h1><?php esc_html_e('DesignsLabz Theme Options', 'designslabz'); ?></h1>
				<h2 class="nav-tab-wrapper">
					<?php
					$tabs = [
						'header' => __('Header', 'designslabz'),
						'footer' => __('Footer', 'designslabz'),
						'404'    => __('404', 'designslabz'),
						'gtm'    => __('Google Tag Manager', 'designslabz'),
					];
					foreach ($tabs as $tab_slug => $label) {
						printf(
							'<a href="?page=dl-theme-options&tab=%1$s" class="nav-tab %2$s">%3$s</a>',
							esc_attr($tab_slug),
							$active_tab === $tab_slug ? 'nav-tab-active' : '',
							esc_html($label)
						);
					}
					?>
				</h2>
				<form method="post" action="options.php">
					<?php
					settings_fields('dl_theme_options_group');
					do_settings_sections('dl-theme-options-' . $active_tab);
					submit_button();
					?>
				</form>
			</div>
			<?php
		}

		/**
		 * Register all settings, sections, and fields.
		 */
		public function register_settings() {
			register_setting(
				'dl_theme_options_group',
				'dl_theme_options',
				[$this, 'sanitize_options']
			);

			// Header Tab
			add_settings_section('dl_header_section', '', '__return_null', 'dl-theme-options-header');
			add_settings_field(
				'dl_header_message_bar',
				__('Header Message Bar', 'designslabz'),
				function() { $this->reusable_block_field('dl_header_message_bar'); },
				'dl-theme-options-header',
				'dl_header_section'
			);
			add_settings_field(
				'dl_header_social_menu',
				__('Header Social Menu', 'designslabz'),
				function() { $this->reusable_block_field('dl_header_social_menu'); },
				'dl-theme-options-header',
				'dl_header_section'
			);
			add_settings_field(
				'dl_header_menu',
				__('Header Menu', 'designslabz'),
				function() { $this->reusable_block_field('dl_header_menu'); },
				'dl-theme-options-header',
				'dl_header_section'
			);

			// Footer Tab
			add_settings_section('dl_footer_section', '', '__return_null', 'dl-theme-options-footer');
			add_settings_field(
				'dl_footer_reusable_block',
				__('Footer Reusable Block', 'designslabz'),
				[$this, 'footer_reusable_block_field'],
				'dl-theme-options-footer',
				'dl_footer_section'
			);

			// 404 Tab
			add_settings_section('dl_404_section', '', '__return_null', 'dl-theme-options-404');
			add_settings_field(
				'dl_404_message',
				__('404 Message', 'designslabz'),
				[$this, 'message_404_field'],
				'dl-theme-options-404',
				'dl_404_section'
			);

			// GTM Tab
			add_settings_section('dl_gtm_section', '', '__return_null', 'dl-theme-options-gtm');
			add_settings_field(
				'dl_gtm_id',
				__('Google Tag Manager ID', 'designslabz'),
				[$this, 'gtm_id_field'],
				'dl-theme-options-gtm',
				'dl_gtm_section'
			);
		}

		/**
		 * Sanitize all options before saving.
		 */
		public function sanitize_options($input) {
			$old = get_option('dl_theme_options', []);
			$new = $old;

			// Only update keys that are present in the form submission
			$all_keys = [
				'dl_header_message_bar' => 'int',
				'dl_header_social_menu' => 'int',
				'dl_header_menu'        => 'int',
				'dl_footer_reusable_block' => 'int',
				'dl_404_message'        => 'html',
				'dl_gtm_id'             => 'text',
			];

			foreach ($all_keys as $key => $type) {
				if (isset($input[$key])) {
					switch ($type) {
						case 'int':
							$new[$key] = absint($input[$key]);
							break;
						case 'html':
							$new[$key] = wp_kses_post($input[$key]);
							break;
						case 'text':
							$new[$key] = sanitize_text_field($input[$key]);
							break;
					}
				}
			}

			return $new;
		}

		/**
		 * Render the 404 Message WYSIWYG.
		 */
		public function message_404_field() {
			$this->editor_field('dl_404_message');
		}

		/**
		 * Helper to render WYSIWYG editors.
		 */
		private function editor_field($key) {
			$options = get_option('dl_theme_options');
			$content = isset($options[$key]) ? $options[$key] : '';
			wp_editor($content, $key, [
				'textarea_name' => "dl_theme_options[$key]",
				'media_buttons' => true,
				'textarea_rows' => 8,
				'teeny' => false,
				'quicktags' => true,
			]);
		}

		/**
		 * Render the GTM ID field.
		 */
		public function gtm_id_field() {
			$options = get_option('dl_theme_options');
			printf('<input type="text" name="dl_theme_options[dl_gtm_id]" value="%s" class="regular-text"/>',
				isset($options['dl_gtm_id']) ? esc_attr($options['dl_gtm_id']) : ''
			);
		}

		/**
		 * Render a reusable block dropdown.
		 */
		public function reusable_block_field($option_key) {
			$options = get_option('dl_theme_options');
			$selected = isset($options[$option_key]) ? (int)$options[$option_key] : 0;
			$blocks = get_posts([
				'post_type' => 'wp_block',
				'posts_per_page' => -1,
				'orderby' => 'title',
				'order' => 'ASC',
			]);

			echo '<select name="dl_theme_options[' . esc_attr($option_key) . ']" class="widefat">';
			echo '<option value="0">— Select a Reusable Block —</option>';
			foreach ($blocks as $block) {
				printf(
					'<option value="%d"%s>%s</option>',
					esc_attr($block->ID),
					selected($selected, $block->ID, false),
					esc_html($block->post_title)
				);
			}
			echo '</select>';

			echo '<div class="dl-preview-container" style="margin-top:1em;">';
			if ($selected) {
				$this->render_block_preview($selected);
			} else {
				echo '<div class="dl-no-preview"><em>No block selected.</em></div>';
			}
			echo '</div>';
		}

		/**
		 * Render Footer Reusable Block field.
		 */
		public function footer_reusable_block_field() {
			$this->reusable_block_field('dl_footer_reusable_block');
		}

		/**
		 * AJAX handler for live block preview.
		 */
		public function get_block_preview_ajax() {
			check_ajax_referer('dl_block_preview', 'nonce');
			if (!current_user_can('edit_theme_options') || !isset($_POST['block_id'])) {
				wp_send_json_error('Invalid request', 403);
			}
			$block_id = absint($_POST['block_id']);
			$this->render_block_preview($block_id, true);
			wp_die();
		}

		/**
		 * Render a block preview markup.
		 */
		private function render_block_preview($block_id, $is_ajax = false) {
			$post = get_post($block_id);

			if (!$post || $post->post_type !== 'wp_block') {
				if ($is_ajax) {
					wp_send_json_error('Block not found');
				}
				echo '<div class="dl-no-preview"><em>Block not found.</em></div>';
				return;
			}

			// Process the content with the_content filters
			$rendered = apply_filters('the_content', $post->post_content);

			if (empty(trim($rendered))) {
				if ($is_ajax) {
					wp_send_json_error('Empty block content');
				}
				echo '<div class="dl-no-preview"><em>No preview available.</em></div>';
				return;
			}

			if ($is_ajax) {
				wp_send_json_success($rendered);
			} else {
				echo $rendered;
			}
		}

	}

	// Initialize the class.
	new DL_Theme_Options();
}

<?php
/**
 * Enqueue frontend, editor, and admin assets with cache busting and site data.
 *
 * @package designslabz
 */

if ( ! class_exists( 'DL_Enqueuer' ) ) {

	class DL_Enqueuer {

		/**
		 * Constructor: hook into WordPress actions.
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_assets' ] );
			add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_editor_assets' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
		}

		/**
		 * Enqueue frontend styles and scripts.
		 */
		public function enqueue_frontend_assets() {
			wp_enqueue_style(
				'dl-theme-style',
				$this->get_asset_url( 'theme-style.css' ),
				[],
				null
			);

			wp_enqueue_script(
				'dl-theme-script',
				$this->get_asset_url( 'theme-scripts.js' ),
				[ 'jquery' ],
				null,
				true
			);

			wp_localize_script( 'dl-theme-script', 'SiteInfo', $this->get_site_info() );
		}

		/**
		 * Enqueue block editor styles and scripts.
		 */
		public function enqueue_editor_assets() {
			wp_enqueue_style(
				'dl-editor-style',
				$this->get_asset_url( 'editor-style.css' ),
				[],
				null
			);

			wp_enqueue_script(
				'dl-editor-script',
				$this->get_asset_url( 'editor-scripts.js' ),
				[
					'wp-blocks',
					'wp-dom-ready',
					'wp-edit-post',
					'jquery',
				],
				null,
				true
			);
		}

		/**
		 * Enqueue admin styles and scripts.
		 */
		public function enqueue_admin_assets() {
			wp_enqueue_style(
				'dl-admin-style',
				$this->get_asset_url( 'admin-style.css' ),
				[],
				null
			);

			wp_enqueue_script(
				'dl-admin-script',
				$this->get_asset_url( 'admin-scripts.js' ),
				[ 'jquery' ],
				null,
				true
			);

			wp_localize_script( 'dl-admin-script', 'SiteInfo', $this->get_site_info() );
		}

		/**
		 * Get site info for localized scripts.
		 *
		 * @return array
		 */
		public function get_site_info() {
			return [
				'homeUrl'        => get_home_url(),
				'restUrl'        => get_rest_url(),
				'themeDirectory' => get_template_directory_uri(),
				'ajaxurl'        => admin_url( 'admin-ajax.php' ),
			];
		}

		/**
		 * Get built asset URL from Webpack manifest.json.
		 *
		 * @param string $filename Logical filename (e.g., 'theme-style.css').
		 * @return string
		 */
		public function get_asset_url( $filename ) {
			$manifest_path = get_template_directory() . '/assets/build/manifest.json';

			if ( ! file_exists( $manifest_path ) ) {
				return '';
			}

			$manifest = json_decode( file_get_contents( $manifest_path ), true );

			if ( isset( $manifest[ $filename ] ) ) {
				return get_template_directory_uri() . '/assets/build/' . $manifest[ $filename ];
			}

			return '';
		}
	}

	// Initialize the enqueuer.
	new DL_Enqueuer();
}

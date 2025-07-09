<?php
/**
 * Class to customize the WordPress login page.
 *
 * @package designslabz
 */

if ( ! class_exists( 'DL_Login_Customizer' ) ) {
	class DL_Login_Customizer {

		/**
		 * Constructor: hook everything.
		 */
		public function __construct() {
			add_action( 'login_enqueue_scripts', [ $this, 'enqueue_styles' ] );
			add_filter( 'login_headerurl', [ $this, 'custom_login_url' ] );
			add_filter( 'login_headertext', [ $this, 'custom_login_title' ] );
		}

		/**
		 * Enqueue custom CSS for login page.
		 */
		public function enqueue_styles() {
			$logo_url = get_template_directory_uri() . '/assets/images/designslabz-logo.png';
			?>
			<style>
				body.login {
					background-color: rgba(136,96,208, 0.3)
				}
				body.login #login h1 a {
					background-image: url('<?php echo esc_url( $logo_url ); ?>');
					background-size: contain;
					width: 100%;
					height: 80px;
				}
				body.login form {
					border-radius: 8px;
					box-shadow: 0 2px 12px rgba(0,0,0,0.1);
				}
			</style>
			<?php
		}

		/**
		 * Change the login logo URL.
		 *
		 * @return string
		 */
		public function custom_login_url() {
			return home_url();
		}

		/**
		 * Change the login logo tooltip text.
		 *
		 * @return string
		 */
		public function custom_login_title() {
			return get_bloginfo( 'name' );
		}
	}

	// Initialize the class.
	new DL_Login_Customizer();
}

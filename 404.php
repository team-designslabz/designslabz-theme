<?php get_header(); ?>

<article class="error404-container">
	<header class="error404-header" role="banner">
		<h1 class="error404-heading">404 Page Not Found</h1>
	</header>
	<div class="error404-content">
		<?php
			$options = get_option('dl_theme_options');

			// Check if a custom 404 message is set in the theme options
			if (!empty($options['dl_404_message'])) {
				echo wp_kses_post($options['dl_404_message']);
			} else {
				// Fallback default content
				?>
				<p><?php esc_html_e('Sorry, but the page you were trying to view does not exist.', 'designslabz'); ?></p>
				<p>
					<?php
					printf(
						wp_kses(
							__('You can return to the <a href="%s">homepage</a> or try searching for what you need.', 'designslabz'),
							['a' => ['href' => []]]
						),
						esc_url(home_url())
					);
					?>
				</p>
			<?php
			}
		?>
	</div>
</article>

<?php
get_footer();

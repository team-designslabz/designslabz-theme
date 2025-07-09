<?php
/**
 * Designslabz Theme Social Header
 *
 * @package designslabz
 */
function dl_display_social_menu_block() {
	$options = get_option('dl_theme_options');
	$block_id = isset($options['dl_header_social_menu']) ? (int)$options['dl_header_social_menu'] : 0;

	if ($block_id) {
		$block_post = get_post($block_id);

		if ($block_post && $block_post->post_type === 'wp_block') {
			$content = $block_post->post_content;
			if (!empty($content)) {
				echo '<div class="social-header">';
					echo render_block(['blockName' => 'core/block', 'attrs' => ['ref' => $block_id]]);
				echo '</div>';
			}
		}
	}
}

dl_display_social_menu_block(); ?>

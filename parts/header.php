<?php
/**
 * Designslabz Theme Header
 *
 * @package designslabz
 */
function dl_display_header_block() {
	$options = get_option('dl_theme_options');
	$block_id = isset($options['dl_header_menu']) ? (int)$options['dl_header_menu'] : 0;

	if ($block_id) {
		$block_post = get_post($block_id);

		if ($block_post && $block_post->post_type === 'wp_block') {
			$content = $block_post->post_content;
			if (!empty($content)) {
				echo '<div class="inner-header-container">';
					echo render_block(['blockName' => 'core/block', 'attrs' => ['ref' => $block_id]]);
				echo '</div>';
			}
		}
	}
}

?>

<div class="main-header-container">
    <?php dl_display_header_block(); ?>
</div>

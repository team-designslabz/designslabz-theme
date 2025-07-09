<?php
/**
 * Designslabz Theme Message Bar
 *
 * @package designslabz
 */
function dl_display_message_bar_block() {
	$options = get_option('dl_theme_options');
	$block_id = isset($options['dl_header_message_bar']) ? (int)$options['dl_header_message_bar'] : 0;

	if ($block_id) {
		$block_post = get_post($block_id);

		if ($block_post && $block_post->post_type === 'wp_block') {
			$content = $block_post->post_content;
			if (!empty($content)) {
				echo '<div class="message-bar-content">';
					echo render_block(['blockName' => 'core/block', 'attrs' => ['ref' => $block_id]]);
				echo '</div>';
			}
		}
	}
}

?>

<div class="message-bar">
    <?php dl_display_message_bar_block(); ?>
</div>

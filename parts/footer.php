<?php
/**
 * Designslabz Theme Footer
 *
 * @package designslabz
 */
function dl_display_footer_block() {
	$options = get_option('dl_theme_options');
	$block_id = isset($options['dl_footer_reusable_block']) ? (int)$options['dl_footer_reusable_block'] : 0;

	if ($block_id) {
		$block_post = get_post($block_id);

		if ($block_post && $block_post->post_type === 'wp_block') {
			$content = $block_post->post_content;
			if (!empty($content)) {
				echo '<div class="footer-reusable-block">';
					echo render_block(['blockName' => 'core/block', 'attrs' => ['ref' => $block_id]]);
				echo '</div>';
			}
		}
	}
}

?>

<footer id="site-footer" class="site-footer footer">
    <?php dl_display_footer_block(); ?>
</footer>

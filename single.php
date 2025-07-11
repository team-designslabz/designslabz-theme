<?php
/**
 * The Template for displaying all single posts
 *
 * @package YourTheme
 */

get_header(); ?>

<section class="blog-header">
	<div class="container">
		<h1 class="blog-title"><?php the_title(); ?></h1>
	</div>
</section>

<section class="blog-content">
	<div class="container">
		<?php if (have_posts()) : ?>
        	<?php while (have_posts()) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="entry-content">
						<?php the_content(); ?>
					</div>
				</article>
				<div class="post-navigation">
					<?php
					// Previous/next post navigation.
					the_post_navigation(array(
						'prev_text' => '<span class="nav-title">' . __('Previous Post', 'designslabz') . '</span>',
						'next_text' => '<span class="nav-title">' . __('Next Post', 'designslabz') . '</span>',
					));
					?>
				</div>
		 	<?php endwhile; ?>
		<?php else : ?>
			<p><?php _e('No posts found.', 'designslabz'); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>

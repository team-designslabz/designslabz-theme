<?php
/**
 * The main template file
 *
 * @package DesignsLabz
 */

get_header();
?>

<main id="primary" class="site-main">
	<section class="archive-header">
		<?php if (is_search()) : ?>
			<h1 class="archive-title">
				<?php
				/* translators: %s: search query */
				printf(
					esc_html__('Search Results for: %s', 'designslabz'),
					'<span>' . get_search_query() . '</span>'
				);
				?>
			</h1>
		<?php elseif (is_category()) : ?>
			<h1 class="archive-title"><?php single_cat_title(); ?></h1>
		<?php elseif (is_tag()) : ?>
			<h1 class="archive-title"><?php single_tag_title(); ?></h1>
		<?php elseif (is_tax()) : ?>
			<h1 class="archive-title"><?php single_term_title(); ?></h1>
		<?php elseif (is_home() && !is_front_page()) : ?>
			<h1 class="archive-title"><?php single_post_title(); ?></h1>
		<?php else : ?>
			<h1 class="archive-title"><?php bloginfo('name'); ?></h1>
		<?php endif; ?>
	</section>


	<section class="archive-filter container">
		<!-- Search Form -->
		<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
			<input type="search" class="search-field" placeholder="<?php esc_attr_e('Search …', 'designslabz'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
			<button type="submit" class="search-submit" aria-label="<?php esc_attr_e('Search', 'designslabz'); ?>">
				<!-- Use an inline SVG icon -->
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
				<circle cx="11" cy="11" r="8"></circle>
				<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
				</svg>
			</button>
		</form>

		<!-- Category Filter -->
		<form class="category-filter" method="get" action="<?php echo esc_url(home_url('/')); ?>">
			<select name="cat" onchange="this.form.submit()">
				<option value=""><?php _e('All Categories', 'designslabz'); ?></option>
				<?php
				$categories = get_categories();
				foreach ($categories as $category) {
					printf(
						'<option value="%1$s"%2$s>%3$s</option>',
						esc_attr($category->term_id),
						selected(get_query_var('cat'), $category->term_id, false),
						esc_html($category->name)
					);
				}
				?>
			</select>
			<noscript><button type="submit"><?php _e('Filter', 'designslabz'); ?></button></noscript>
		</form>
	</section>

	<section class="archive-posts-grid container">
		<?php if (have_posts()) : ?>
			<div class="posts-grid">
				<?php
				while (have_posts()) :
					the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" class="post-thumbnail">
								<?php the_post_thumbnail('medium_large'); ?>
							</a>
						<?php else : ?>
							<a href="<?php the_permalink(); ?>" class="post-thumbnail">
								<img src="<?php echo get_template_directory_uri(); ?>/assets/images/designslabz.png" alt="<?php the_title_attribute(); ?>">
							</a>
						<?php endif; ?>
						<div class="entry-header">
							<?php the_category(', '); ?>
							<h2 class="entry-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>
						</div>
						<div class="entry-summary">
							<?php the_excerpt(); ?>
						</div>
						<div class="entry-footer">
							<a class="read-more" href="<?php the_permalink(); ?>"><?php _e('Read More', 'designslabz'); ?></a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<?php
			the_posts_pagination([
				'mid_size'  => 2,
				'prev_text' => '<span class="screen-reader-text">' . __('Previous', 'designslabz') . '</span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/></svg>',
				'next_text' => '<span class="screen-reader-text">' . __('Next', 'designslabz') . '</span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/></svg>',
			]);
			?>
		<?php else : ?>
			<p><?php _e('No posts found.', 'designslabz'); ?></p>
		<?php endif; ?>
	</section>
</main>

<?php get_footer(); ?>

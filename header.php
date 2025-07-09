<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<a class="screen-reader-text" href="#main">Skip to content</a>

	<header id="site-header" class="site-header">
		<?php get_template_part('parts/message-bar', null, $args); ?>
		<?php get_template_part('parts/social-header', null, $args); ?>
		<?php get_template_part('parts/header', null, $args); ?>
	</header>

	<main id="main" class="main" role="main">

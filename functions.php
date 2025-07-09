<?php
/**
 * Designslabz Theme Functions
 *
 * @package designslabz
 */
$includes = sprintf('%s/includes', get_template_directory());

// Load the files from the includes directory
require $includes . '/admin.php';
require $includes . '/enqueuer.php';
require $includes . '/theme-options.php';
require $includes . '/theme-setup.php';

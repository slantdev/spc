<?php

/**
 * REQUIRED FILES
 * Include required files.
 */
require get_template_directory() . '/inc/theme-setup.php';
require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/acf.php';
require get_template_directory() . '/inc/enqueue.php';
require get_template_directory() . '/inc/ajax.php';
require get_template_directory() . '/inc/woocommerce.php';

/**
 * Disable Gutenberg
 */
add_filter('use_block_editor_for_post_type', 'prefix_disable_gutenberg', 10, 2);
function prefix_disable_gutenberg($current_status, $post_type)
{
	// Use your post type key instead of 'product'
	if ($post_type === 'post' || $post_type === 'page' || $post_type === 'where-are-they' || $post_type === 'faq') return false;
	return $current_status;
}

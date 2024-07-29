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
//require get_template_directory() . '/inc/woocommerce.php';

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

/**
 * Remove Gutenberg Block Library CSS from loading on the frontend
 */
function spc_remove_wp_block_library_css()
{
	wp_dequeue_style('wp-block-library');
	wp_dequeue_style('wp-block-library-theme');
	wp_dequeue_style('wc-blocks-style'); // Remove WooCommerce block CSS
	wp_dequeue_style('global-styles');
}
add_action('wp_enqueue_scripts', 'spc_remove_wp_block_library_css', 100);

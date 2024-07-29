<?php

/**
 * Enqueue theme assets.
 */
function spc_enqueue_scripts()
{
  $theme = wp_get_theme();

  wp_enqueue_style('dashicons');
  wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css', array(), '8.4.7');
  //wp_enqueue_style('animate', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css', array(), '4.1.1');
  //wp_enqueue_style('fancybox', 'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css', array(), '5.0.0');
  //wp_enqueue_style('fancybox', spc_asset('js/fancybox.css'), array(), $theme->get('Version'));
  wp_enqueue_style('spc', spc_asset('css/app.css'), array(), $theme->get('Version'));

  wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js', array(), '8.4.7');
  //wp_enqueue_script('fancybox', 'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js', array(), '5.0.0');
  wp_enqueue_script('counterup', spc_asset('js/counterup.js'), array(), '2.0.2', false);
  // wp_enqueue_script('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), $theme->get('Version'), true);

  //wp_enqueue_script('fancybox', spc_asset('js/fancybox.js'), array(), $theme->get('Version'));
  wp_enqueue_script('spc', spc_asset('js/app.js'), array('jquery'), $theme->get('Version'));
}

add_action('wp_enqueue_scripts', 'spc_enqueue_scripts', 9999);

//add_filter("script_loader_tag", "add_module_to_my_script", 10, 3);
function add_module_to_my_script($tag, $handle, $src)
{
  if ("fancybox" === $handle) {
    $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
  }

  return $tag;
}

function spc_google_fonts()
{
  echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . PHP_EOL;
  echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . PHP_EOL;
  echo '<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">' . PHP_EOL;
}
add_action('wp_head', 'spc_google_fonts');

/**
 * Get asset path.
 *
 * @param string  $path Path to asset.
 *
 * @return string
 */
function spc_asset($path)
{
  if (wp_get_environment_type() === 'production') {
    return get_stylesheet_directory_uri() . '/assets/' . $path;
  }

  return add_query_arg('time', time(),  get_stylesheet_directory_uri() . '/assets/' . $path);
}

function spc_admin_styles()
{
  global $pagenow;
  $current_page = get_current_screen();
  $theme = wp_get_theme();
  wp_enqueue_style('admin_css', get_template_directory_uri() . '/assets/css/admin-style.css', false, filemtime(get_stylesheet_directory() . '/assets/css/admin-style.css'));
  wp_enqueue_style('admin_gfonts', 'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Poppins:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap', false, $theme->get('Version'));
  if (
    ($current_page->post_type === 'page' && ($pagenow === 'post-new.php' || $pagenow === 'post.php')) ||
    ($current_page->post_type === 'campaign' && ($pagenow === 'post-new.php' || $pagenow === 'post.php')) ||
    ($current_page->post_type === 'help-advice' && ($pagenow === 'post-new.php' || $pagenow === 'post.php'))
  ) {
    wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css', array(), '8.4.7');
    wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js', array(), '8.4.7');
    wp_enqueue_style('acf_layouts', get_template_directory_uri() . '/assets/css/acf-layouts.css', false, filemtime(get_stylesheet_directory() . '/assets/css/acf-layouts.css'));
  }
}
add_action('admin_enqueue_scripts', 'spc_admin_styles');

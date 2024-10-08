<?php
/*
 * Page Settings
 */
$term_id = '';
if (is_tax()) {
  $term_id = ($queried_object = get_queried_object()) ? $queried_object->term_id : null;
}
$the_id = $term_id ? 'term_' . $term_id : get_the_ID();

$breadcrumbs = $args['breadcrumbs'] ?? false;
$enable_page_header = false;

if (is_singular('post')) :
  $enable_page_header = true;
  $title = get_the_title();
  // $background_image_url = get_the_post_thumbnail_url($the_id, 'full');
  // $background_position = 'center';
  $background_overlay = 'rgba(243,241,239,0.6)';
  $background_image_url = false;
  //$background_overlay = false;
  $bg_image_class = ' object-center';
  $show_breadcrumbs = true;
  $breadcrumbs_style = '--breadcrumbs-text-color:#020044;--breadcrumbs-separator-color:#FF6347;';
  $title_style = 'color:#020044;';
  $show_title = true;
  $show_description = false;
  $use_salesforce = false;
  $description = false;
  $show_language_switcher = false;

elseif (is_tax('product_cat')) :

  $enable_page_header = true;
  //$title = get_the_title();

  //$terms = get_the_terms(get_the_ID(), 'product_cat');
  $term = get_queried_object();
  //preint_r($term);
  $title = $term->name;
  $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
  //preint_r($thumbnail_id);
  $background_image_url = wp_get_attachment_url($thumbnail_id);
  $background_position = 'center';
  $background_overlay = 'rgba(243,241,239,0.6)';
  $bg_image_class = ' object-center';
  $show_breadcrumbs = true;
  $breadcrumbs_style = '--breadcrumbs-text-color:#020044;--breadcrumbs-separator-color:#FF6347;';
  $title_style = 'color:#020044;';
  $show_title = true;
  $show_description = false;
  $use_salesforce = false;
  $description = false;
  $show_language_switcher = false;

elseif (is_singular('product')) :

  $enable_page_header = true;
  //$title = get_the_title();

  $terms = get_the_terms(get_the_ID(), 'product_cat');
  //preint_r($terms);
  $title = $terms[0]->name;
  // $background_image_url = get_the_post_thumbnail_url($the_id, 'full');
  // $background_position = 'center';
  $background_overlay = 'rgba(243,241,239,0.6)';
  $background_image_url = false;
  //$background_overlay = false;
  $bg_image_class = ' object-center';
  $show_breadcrumbs = true;
  $breadcrumbs_style = '--breadcrumbs-text-color:#020044;--breadcrumbs-separator-color:#FF6347;';
  $title_style = 'color:#020044;';
  $show_title = true;
  $show_description = false;
  $use_salesforce = false;
  $description = false;
  $show_language_switcher = false;

elseif (($page_header = get_field('page_header', $the_id)) && isset($page_header['enable_page_header']) && $page_header['enable_page_header']) :
  $enable_page_header = true;
  $page_header_settings = $page_header['page_header_settings'] ?? [];

  $breadcrumbs_settings = $page_header_settings['breadcrumbs'] ?? [];
  $show_breadcrumbs = $breadcrumbs_settings['show_breadcrumbs'] ?? false;
  $breadcrumbs_text_color = $breadcrumbs_settings['breadcrumbs_text_color'] ?? '';
  $separator_color = $breadcrumbs_settings['separator_color'] ?? '';
  $breadcrumbs_style = ($breadcrumbs_text_color || $separator_color) ? '--breadcrumbs-text-color:' . $breadcrumbs_text_color . ';' . '--breadcrumbs-separator-color:' . $separator_color . ';' : '';

  $title_settings = $page_header_settings['title'] ?? [];
  $show_title = $title_settings['show_title'] ?? false;
  $title = $title_settings['title'] ?? '';
  $title_color = $title_settings['title_color'] ?? '';
  $title_style = $title_color ? 'color:' . $title_color . ';' : '';
  if (!$title) {
    $title = is_tax() ? get_term($the_id)->name : get_the_title();
  }

  // $buttons_settings = $page_header_settings['buttons'] ?? [];
  // $show_buttons = $buttons_settings['show_buttons'] ?? false;
  // $buttons = $buttons_settings['buttons'] ?? '';

  $background_settings = $page_header_settings['background'] ?? [];
  $background_image = $background_settings['background_image'] ?? '';
  $background_image_url = $background_image['url'] ?? '';
  $background_mobile = $background_settings['background_mobile'] ?? '';
  $background_mobile_url = $background_mobile['url'] ?? '';
  $background_position = $background_settings['background_position'] ?? '';
  $background_overlay = $background_settings['background_overlay'] ?? '';
  $bg_image_class = $background_position ? ' object-' . $background_position : '';


endif;
?>

<?php if ($enable_page_header) : ?>
  <section class="section-page-header relative">
    <?php if ($background_image_url) : ?>
      <div class="absolute inset-0 z-0">
        <img class="object-cover w-full !h-full <?php echo $bg_image_class ?>" src="<?php echo $background_image_url ?>" alt="">
      </div>
    <?php endif; ?>
    <div class="relative z-auto">
      <?php if ($background_overlay) : ?>
        <div class="absolute inset-0 z-0">
          <div class="h-full w-full" style="background-color: <?php echo $background_overlay ?>;"></div>
        </div>
      <?php endif; ?>
      <div class="container max-w-screen-2xl relative z-auto">
        <div class="flex flex-col lg:flex-row lg:gap-x-8 xl:gap-x-12">
          <div class="w-full lg:w-2/3">
            <?php if ($show_breadcrumbs || $show_title) : ?>
              <div class="flex flex-col pt-20 pb-6 lg:pt-36 lg:pb-36">
                <?php if ($show_breadcrumbs && function_exists('yoast_breadcrumb')) : ?>
                  <?php yoast_breadcrumb('<div class="breadcrumbs text-sm lg:text-base mb-4" style="' . $breadcrumbs_style . '">', '</div>'); ?>
                <?php endif; ?>
                <?php if ($show_title && $title) : ?>
                  <h1 class="text-3xl xl:text-4xl leading-[1.1em] font-bold" style="<?php echo $title_style ?>"><?php echo $title ?></h1>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php endif;

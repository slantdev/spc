<?php

function spc_acf_init()
{
  // if (function_exists('get_field')) {
  //   if (defined('GOOGLE_MAPS_API')) {
  //     acf_update_setting('google_api_key', GOOGLE_MAPS_API);
  //   }
  // }

  // Disable ACFE Modules
  acf_update_setting('acfe/modules/options', false);
  acf_update_setting('acfe/modules/block_types', false);
  acf_update_setting('acfe/modules/options_pages', false);
  acf_update_setting('acfe/modules/post_types', false);
  acf_update_setting('acfe/modules/taxonomies', false);
  acf_update_setting('acfe/modules/forms', false);
}
add_action('acf/init', 'spc_acf_init');


/*
 * Add color picker pallete on admin
 */
function spc_acf_input_admin_footer()
{
  $palette_fields = [
    'primary_color',
    'secondary_color',
    'tertiary_color',
    'fourth_color',
    'fifth_color',
    'body_text_color',
  ];

  $additional_color = get_field('additional_color', 'option');

  $primary_palette_array = [];
  foreach ($palette_fields as $field) {
    $color = get_field($field, 'option');
    if ($color) {
      $primary_palette_array[] = $color;
    }
  }

  $additional_color_array = [];
  if ($additional_color) {
    foreach ($additional_color as $color) {
      $additional_color_array[] = $color['color'];
    }
  }

  $primary_palette = implode("', '", $primary_palette_array);
  $additional_palette = implode("', '", $additional_color_array);

?>
  <script type="text/javascript">
    (function($) {

      acf.add_filter('color_picker_args', function(args, $field) {

        args.palettes = ['#000000', '#FFFFFF', '<?php echo $primary_palette ?>', '<?php echo $additional_palette ?>']

        return args;

      });

    })(jQuery);
  </script>
<?php
}
add_action('acf/input/admin_footer', 'spc_acf_input_admin_footer');

/*
 * ACF Icon Picker
 * Modify the path to the icons directory
 * https://github.com/houke/acf-icon-picker
 */
add_filter('acf_icon_path_suffix', 'acf_icon_path_suffix');
function acf_icon_path_suffix($path_suffix)
{
  return 'assets/icons/content/';
}

/*
 * ACF Extended Layout Thumbnails
 * https://www.acf-extended.com/features/fields/flexible-content/advanced-settings
 * @int/string  $thumbnail  Thumbnail ID/URL
 * @array       $field      Field settings
 * @array       $layout     Layout settings
 */
$layouts = ['adopt_cats', 'card_slider', 'cta', 'faqs', 'hero_slider', 'posts_card', 'posts_grid', 'shop', 'testimonial', 'two_columns'];
foreach ($layouts as $prefix) {
  $functionName = function ($thumbnail, $field, $layout) use ($prefix) {
    return get_stylesheet_directory_uri() . '/assets/images/layouts/' . $prefix . '.jpg';
  };
  add_filter('acfe/flexible/thumbnail/layout=' . $prefix, $functionName, 10, 3);
}


/* helper function to get formidable forms to ACF: */
function get_formidable_forms()
{
  $results = array();
  foreach (FrmForm::get_published_forms() as $published_form) {
    $results[$published_form->id] = $published_form->name;
  }
  return $results;
}
/* auto populate acf field with form IDs */
function load_forms_function($field)
{
  $result = get_formidable_forms();
  if (is_array($result)) {
    $field['choices'] = array();
    foreach ($result as $key => $match) {
      $field['choices'][$key] = $match;
    }
  }
  return $field;
}
//add_filter('acf/load_field/name=select_formidable_form', 'load_forms_function');
